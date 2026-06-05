<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        return view('admin.articles.index', compact('articles'));
    }




    // -----------------------------------------------------
    // INSPECT
    // -----------------------------------------------------

    public function inspect()
    {
        $articles = Article::latest()->paginate(10);

        return view('admin.articles.inspect', compact('articles'));
    }




    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------

    public function create()
    {
        $categories = Category::query()
            ->orderBy('name')
            ->pluck('name', 'id');
        
        return view('admin.articles.create', compact('categories'));
    }




    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {   


    // dd('Submitted');
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120', 'regex:/^[\pL\pN\s\-\:\,\.\'\"\!\?]+$/u'],
            'slug' => ['nullable', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:articles,slug'],
            'excerpt' => ['nullable', 'string', 'min:50', 'max:300'],
            'content' => 'nullable',
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'cropped_image' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'is_published' => ['nullable', 'boolean']
        ]);

        // dd($data);
        // Save cropped image


        

        $imagePath = null;

if ($request->cropped_image) {

    $image = $request->cropped_image;

    $image = preg_replace(
        '#^data:image/\w+;base64,#i',
        '',
        $image
    );

    $imageData = base64_decode($image);

    if ($imageData === false) {
        throw new \Exception('Invalid base64 image');
    }

    $filename = \Illuminate\Support\Str::uuid() . '.jpg';

    \Illuminate\Support\Facades\Storage::disk('public')->put(
        'articles/' . $filename,
        $imageData
    );

    $imagePath = 'articles/' . $filename;
}






        $data['featured_image'] = $imagePath;

        

        $data['user_id'] = auth()->id();
        $data['hex'] = $generator->uniqueHexId();
        

        // Get boolean from visibility select and set 'is_published' to now()        
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = now();
        }
        

// dd($request->all());        
        Article::create($data);

        return redirect()->route('admin.articles.index')
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

        return view('admin.articles.edit', compact('categories', 'article'));

        // return view('admin.articles.edit', [
        //     'categories' => $categories,
        //     'article' => $article
        // ]);

    }




    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120', 'regex:/^[\pL\pN\s\-\:\,\.\'\"\!\?]+$/u'],
            'slug' => ['required', 'string', 'max:150', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('articles', 'slug')->ignore($article->id),],
            'excerpt' => ['required', 'string', 'min:50', 'max:300'],
            'content' => 'required',
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'is_published' => ['required', 'boolean']
        ]);

        if ($data['is_published'] && ! $article->published_at) {
            $data['published_at'] = now();
        }
        

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated');
    }



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

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted');
    }
}
