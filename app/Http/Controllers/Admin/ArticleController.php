<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\CriminalCase;
use App\Models\Image;
use App\Services\ImageOptimizer;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;


class ArticleController extends Controller
{
    
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index(Request $request)
    {
        $articles = Article::query();

        // Filter by case
        if ($request->filled('case')) {
            $criminalCase = CriminalCase::where('slug', $request->case)->firstOrFail();
            $articles->whereBelongsTo($criminalCase);
            $title = $criminalCase->name . ' Case Articles';
            $subtitle = 'Manage and organize articles about the ' . $criminalCase->name . ' case.';
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $articles->whereBelongsTo($category);
            $title = $category->singular_name . ' Articles';
            $subtitle = 'Manage and organize articles in the ' . $category->singular_name . ' category.';
        }

        $articles = $articles->latest()->paginate();

        return view('articles.admin-index', [
            'articles' => $articles,
            'title' => $title ?? null,
            'subtitle' => $subtitle ?? null,
        ]);
    }


    // -----------------------------------------------------
    // SHOW
    // -----------------------------------------------------

    public function show(Article $article)
    {
        return view('articles.admin-show', compact('article'));
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------

    public function create(Request $request)
    {   
        $criminalCases = CriminalCase::orderBy('name')->pluck('name', 'id');
        $categories = Category::orderBy('name')->pluck('name', 'id');
        
        if ($request->filled('case')) {
            $selectedCase = CriminalCase::where('slug', $request->case)->first();
            $subtitle = 'Create a new article for the ' . $selectedCase->name . ' case.';
        }

        if ($request->filled('category')) {
            $selectedCategory = Category::where('slug', $request->category)->first();
            $subtitle = 'Create a new article for the ' . Str::singular($selectedCategory->name) . ' category.';
        }
        
        return view('articles.create', [
            'criminalCases' => $criminalCases,
            'categories' => $categories,
            'selectedCase' => $selectedCase ?? null,
            'selectedCategory' => $selectedCategory ?? null,
            'title' => $title ?? null,
            'subtitle' => $subtitle ?? null,
        ]);
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {   
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120', 'regex:/^[\pL\pN\s\-\:\,\.\'\"\!\?]+$/u'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:articles,slug'],
            'excerpt' => ['nullable', 'string', 'min:50', 'max:500'],
            'content' => ['nullable', 'string'],
            'cropped_image' => ['nullable', 'string'],
            'criminal_case_id' => ['nullable', 'integer', 'exists:criminal_cases,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:200'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        Article::create([
            'hex' => $generator->uniqueHexId(),
            'title' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'criminal_case_id' => $data['criminal_case_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'user_id' => auth()->id(),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('status', [
                'type' => 'success', 
                'message' => 'Article created!'
            ]);
    }


    // -----------------------------------------------------
    // EDIT
    // -----------------------------------------------------

    public function edit(Article $article)
    {   
        $criminalCases = CriminalCase::orderBy('name')->pluck('name', 'id');
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('articles.edit', [
            'article' => $article,
            'criminalCases' => $criminalCases,
            'categories' => $categories,
        ]);
    }


    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    public function update(Request $request, Article $article)
    {   
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120', 'regex:/^[\pL\pN\s\-\:\,\.\'\"\!\?]+$/u'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('articles', 'slug')->ignore($article->id),],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required'],
            'criminal_case_id' => ['nullable', 'integer', 'exists:criminal_cases,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'is_published' => ['required', 'boolean']
        ]);

        // Set published date if first publish

        if ($data['is_published'] && ! $article->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Article updated.',
            ]);
            
    }


     // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Article $article)
    {
        foreach ($article->images as $image) {
            if ($image->path && Storage::disk('public')->exists($image->display_path)) {
                Storage::disk('public')->delete($image->display_path);
            }
        }

        $article->images()->delete();

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Article deleted.',
            ]);
    }









    // -----------------------------------------------------
    // IMAGES INDEX
    // -----------------------------------------------------

    public function imagesIndex(Article $article)
    {
        $images = $article->images()
            ->latest()
            ->paginate(10);

        return view('articles.images-index', [
            'article' => $article,
            'images' => $images,
        ]);
    }


    // -----------------------------------------------------
    // CREATE IMAGE
    // -----------------------------------------------------

    public function createImage(Article $article)
    {   
        return view('articles.create-image', [
            'article' => $article,
        ]);
    }


    // -----------------------------------------------------
    // STORE ARTICLE IMAGE
    // -----------------------------------------------------

    public function storeImage(Request $request, Article $article, ImageOptimizer $optimizer)
    {

        $validated = $request->validate([
            'cropped_image' => ['required', 'string'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'credit_name' => ['nullable', 'string', 'max:255'],
            'credit_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $image = preg_replace(
            '#^data:image/\w+;base64,#i',
            '',
            $validated['cropped_image']
        );

        $imageData = base64_decode($image);

        if ($imageData === false) {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Invalid image data.',
            ]);
        }

        $filename = (string) Str::uuid();
        $path = "articles/{$article->hex}/{$filename}";

        $manager = ImageManager::usingDriver(Driver::class);
        $decodedImage = $manager->decodeBinary($imageData);

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $decodedImage->encode(
                new JpegEncoder(quality: 90)
            )
        );

        if (! empty($validated['is_featured'])) {
            $article->images()->update([
                'is_featured' => false,
            ]);
        }

        $image = $article->images()->create([
            'image_path' => $path,
            'caption' => $validated['caption'],
            'alt_text' => $validated['alt_text'],
            'credit_name' => $validated['credit_name'],
            'credit_url' => $validated['credit_url'],
            'is_featured' => $validated['is_featured'] ?? false,
        ]);

        try {
            $optimizer->optimizeImage($image);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('admin.articles.images.index', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded.',
            ]);
    }


    // -----------------------------------------------------
    // OPTIMIZE IMAGE
    // -----------------------------------------------------

    public function optimizeImage(Article $article, Image $image)
    {
        try {

            app(ImageOptimizer::class)->optimize(
                $image->image_path
            );

            $image->update([
                'has_multiformat' => true,
            ]);

            return back()->with('status', [
                'type' => 'success',
                'message' => 'Image optimized successfully.',
            ]);

        } catch (\Throwable $e) {

            report($e);

            return back()->with('status', [
                'type' => 'error',
                'message' => 'Failed to optimize image.',
            ]);
        }
    }

    
    // -----------------------------------------------------
    // EDIT ARTICLE IMAGE
    // -----------------------------------------------------

    public function editImage(Article $article, Image $image)
    {   
        // Header actions
        $title = 'Edit Image';
        $subtitle = 'Replace the image or update its details and metadata.';
        $actions = [
            'back' => [
                'label' => 'Back to Images',
                'href' => route('admin.articles.images.index', $article),
                'variant' => 'ghost',
            ]
        ];

        return view('articles.edit-image', [
            'article' => $article,
            'image' => $image,
            'title' => $title,
            'subtitle' => $subtitle,
            'actions' => $actions
        ]);
    }


    // -----------------------------------------------------
    // UPDATE ARTICLE IMAGE
    // -----------------------------------------------------

    public function updateImage(Request $request, Article $article, Image $image)
    {

        $validated = $request->validate([
            'cropped_image' => ['nullable', 'string'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'credit_name' => ['nullable', 'string', 'max:255'],
            'credit_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        // Replace image if a new one was uploaded
        if (!empty($validated['cropped_image'])) {

            $imageData = preg_replace(
                '#^data:image/\w+;base64,#i',
                '',
                $validated['cropped_image']
            );

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return back()->withErrors([
                    'cropped_image' => 'Invalid image data.',
                ]);
            }

            // Delete old image
            if ($image->image_path && Storage::disk('public')->exists($image->display_path)) {
                Storage::disk('public')->delete($image->display_path);
            }

            // Save new image
            $filename = Str::uuid() . '.jpg';
            $path = "articles/{$filename}";

            Storage::disk('public')->put($path, $imageData);

            $image->path = $path;
        }

        // Only one featured image per article
        if ($request->boolean('is_featured')) {
            $article->images()
                ->whereKeyNot($image->id)
                ->update([
                    'is_featured' => false,
                ]);
        }

        $image->caption = $validated['caption'];
        $image->alt_text = $validated['alt_text'];
        $image->credit_name = $validated['credit_name'];
        $image->credit_url = $validated['credit_url'];
        $image->is_featured = $request->boolean('is_featured');

        $image->save();

        return redirect()
            ->route('admin.articles.images', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image updated successfully!',
            ]);
    }



    public function destroyImage(Article $article, Image $image)
    {
        $image = $article->images()
            ->whereKey($image->id)
            ->firstOrFail();

        $sizes = [
            160,
            320,
            480,
            640,
            800,
            1200,
        ];

        $files = [
            "{$image->image_path}.jpg",
        ];

        foreach ($sizes as $size) {
            $files[] = "{$image->image_path}-{$size}.webp";
            $files[] = "{$image->image_path}-{$size}.avif";
        }

        Storage::disk('public')->delete($files);

        $image->delete();

        // Remove the article image directory if it is now empty.
        $directory = dirname($image->image_path);

        if (empty(Storage::disk('public')->files($directory))) {
            Storage::disk('public')->deleteDirectory($directory);
        }

        return redirect()
            ->route('admin.articles.images.index', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image deleted.',
            ]);
    }
    


   

}
