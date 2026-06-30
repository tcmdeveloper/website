<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


// -----------------------------------------------------
// CATEGORY CONTROLLER (ADMIN)
// -----------------------------------------------------

class CategoryController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $categories = Category::withCount('publishedArticles as article_count')->orderBy('name')->paginate(10);
        
        return view('categories.admin-index', [
            'categories' => $categories
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
        return view('categories.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['required', 'string', 'max:300'],
        ]);

        // Array of tailwind colors
        $colors = [
            'red',
            'orange',
            'amber',
            'yellow',
            'lime',
            'green',
            'emerald',
            'teal',
            'cyan',
            'sky',
            'blue',
            'indigo',
            'violet',
            'purple',
            'fuchsia',
            'pink',
            'rose',
        ];

        $validated['color'] = $colors[array_rand($colors)];
        $validated['slug'] = Str::slug($validated['name']);
        $validated['hex'] = $generator->uniqueHexId();

        // Ensure the generated slug is unique
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:categories,slug'],
        ])->validate();

        Category::create($validated);

        return redirect( route('admin.categories.index') )->with('success', 'New category added!');

    }


    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Category $category)
    {
        $category->articles()->update([
            'category_id' => null
        ]);

        $category->delete();
        
        return redirect()
            ->route('admin.categories.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Category deleted!',
            ]);
    }

    
}