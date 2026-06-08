<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


// -----------------------------------------------------
// ARTICLE CONTROLLER (ADMIN)
// -----------------------------------------------------

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

    public function inspect()
    {
        $articles = Article::latest()->paginate(10);

        return view('articles.inspect', compact('articles'));
    }

    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------

    public function create()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->pluck('name', 'id');
        
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
            'content' => 'nullable',
            'cropped_image' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'is_published' => ['nullable', 'boolean']
        ]);

        // Save cropped image
        $imagePath = null;

        if ($request->cropped_image) {

            $image = $request->cropped_image;
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
            $imageData = base64_decode($image);

            if ($imageData === false) {
                throw new \Exception('Invalid base64 image');
            }

            $filename = \Illuminate\Support\Str::uuid() . '.jpg';

            Storage::disk('public')->put(
                'articles/' . $filename,
                $imageData
            );

            $imagePath = 'articles/' . $filename;
        
        }
        
        $data['featured_image'] = $imagePath;

        $data['user_id'] = auth()->id();
        $data['hex'] = $generator->uniqueHexId();
        
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        return redirect()->route('articles.index')
            ->with('success', 'Article created');
    }


    // -----------------------------------------------------
    // EDIT
    // -----------------------------------------------------

    public function edit(Article $article)
    {
        $categories = Category::query()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('articles.edit', [
            'categories' => $categories,
            'article' => $article,
        ]);

    }


    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    public function update(Request $request, Article $article)
    {   

        // 1. Validate form input

        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120', 'regex:/^[\pL\pN\s\-\:\,\.\'\"\!\?]+$/u'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('articles', 'slug')->ignore($article->id),],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required'],
            'cropped_image' => ['nullable', 'string', 'max:255'],
            'featured_image_id' => [
                'nullable',
                'integer',
                Rule::exists('article_images', 'id'),
            ],
            'featured_image_caption' => ['nullable', 'string', 'max:255'],
            'featured_image_alt_text' => ['nullable', 'string', 'max:255'],
            'featured_image_source' => ['nullable', 'string', 'max:255'],
            'featured_image_source_url' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'is_published' => ['required', 'boolean']
        ]);
    

        // 2. Gather image data

        $imageData = [
            'id' => $data['featured_image_id'] ?? null,
            'cropped_image' => $data['cropped_image'] ?? null,
            'caption' => $data['featured_image_caption'] ?? null,
            'alt_text' => $data['featured_image_alt_text'] ?? null,
            'source' => $data['featured_image_source'] ?? null,
            'source_url' => $data['featured_image_source_url'] ?? null,
        ];


        // 3. Remove image data from input

        unset(
            $data['cropped_image'],
            $data['featured_image_id'],
            $data['featured_image_caption'],
            $data['featured_image_alt_text'],
            $data['featured_image_source'],
            $data['featured_image_source_url']
        );


        // 4. Set published date if first publish

        if ($data['is_published'] && ! $article->published_at) {
            $data['published_at'] = now();
        }


        // 5. Update article

        $article->update($data);



        // 6. Get the image to update

        $image = null;

        if (!empty($imageData['id'])) {

            $image = $article->images()
                ->whereKey($imageData['id'])
                ->first();
            
            if ($image) {
                $image->update([
                    'caption' => $imageData['caption'],
                    'alt_text' => $imageData['alt_text'],
                    'source' => $imageData['source'],
                    'source_url' => $imageData['source_url'],
                ]);

            }

        }







        return redirect()->route('articles.admin-index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Article updated successfully!',
            ]);
    }


    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image']
        ]);

        $path = $request->file('image')
            ->store('articles', 'public');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }


    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()
            ->route('articles.index')
            ->with('success', 'Article deleted');
    }

}
