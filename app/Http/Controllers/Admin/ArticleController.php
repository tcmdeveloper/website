<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Category;
use App\Models\CriminalCase;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class ArticleController extends Controller
{
    
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $articles = Article::latest()->paginate(10);

        return view('articles.admin-index', compact('articles'));
    }


    // -----------------------------------------------------
    // INSPECT
    // -----------------------------------------------------

    public function show(Article $article)
    {
        return view('articles.admin-show', compact('article'));
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------

    public function create()
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        
        return view('articles.create', compact('categories'));
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {   
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120', 'regex:/^[\pL\pN\s\-\:\,\.\'\"\!\?]+$/u'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:articles,slug'],
            'excerpt' => ['nullable', 'string', 'min:50', 'max:300'],
            'content' => ['nullable', 'string'],
            'cropped_image' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $article = Article::create([
            'title' => $data['title'],
            'slug' => $data['slug'] ?? Str::slug($data['title']),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
            'user_id' => auth()->id(),
            'hex' => $generator->uniqueHexId(),
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
                'message' => 'Article updated!',
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
    // SELECT IMAGE
    // -----------------------------------------------------

    public function selectImage(Article $article)
    {
        return view('articles.create-image', compact('article'));
    }


    // -----------------------------------------------------
    // STORE ARTICLE IMAGE
    // -----------------------------------------------------
    public function storeImage(Request $request, Article $article)
    {
        $validated = $request->validate([
            'cropped_image' => ['required', 'string'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $image = preg_replace(
            '#^data:image/\w+;base64,#i',
            '',
            $validated['cropped_image']
        );

        $imageData = base64_decode($image);

        if ($imageData === false) {
            return back()->withErrors([
                'cropped_image' => 'Invalid image data.',
            ]);
        }

        $filename = Str::uuid() . '.jpg';
        $path = "articles/{$filename}";

        Storage::disk('public')->put($path, $imageData);

        // If this image is becoming featured, clear any existing featured image.
        if (!empty($validated['is_featured'])) {
            $article->images()->update([
                'is_featured' => false,
            ]);
        }

        $article->images()->create([
            'path' => $path,
            'caption' => $validated['caption'],
            'alt_text' => $validated['alt_text'],
            'source' => $validated['source'],
            'source_url' => $validated['source_url'],
            'is_featured' => $validated['is_featured'] ?? false,
        ]);

        return redirect()
            ->route('admin.articles.images', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded successfully!',
            ]);
    }

    
    // -----------------------------------------------------
    // EDIT ARTICLE IMAGE
    // -----------------------------------------------------

    public function editImage(Article $article, ArticleImage $image)
    {
        return view('articles.edit-image', compact('article', 'image'));
    }


    // -----------------------------------------------------
    // UPDATE ARTICLE IMAGE
    // -----------------------------------------------------

    public function updateImage(Request $request, Article $article, ArticleImage $image)
    {
        abort_unless($image->article_id === $article->id, 404);

        $validated = $request->validate([
            'cropped_image' => ['nullable', 'string'],
            'caption' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
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
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
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
        $image->source = $validated['source'];
        $image->source_url = $validated['source_url'];
        $image->is_featured = $request->boolean('is_featured');

        $image->save();

        return redirect()
            ->route('admin.articles.images', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image updated successfully!',
            ]);
    }



    public function destroyImage(Article $article, ArticleImage $image)
    {
        $image = $article->images()
            ->whereKey($image->id)
            ->firstOrFail();

        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return redirect()
            ->route('admin.articles.images', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image deleted successfully!',
            ]);
    }
    


    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Article $article)
    {
        foreach ($article->images as $image) {
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $article->images()->delete();

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Article deleted!',
            ]);
    }

}
