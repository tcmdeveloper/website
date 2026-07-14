<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Image;
use App\Services\ImageOptimizer;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;


// -----------------------------------------------------
// CRIMINAL CASE CONTROLLER (ADMIN)
// -----------------------------------------------------

class CriminalCaseController extends Controller
{ 

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index(Request $request)
    {
        $criminalCases = CriminalCase::query();

        //
        // Put filters here if required.
        //

        $criminalCases = $criminalCases->latest()->withCount('articles')->paginate();
        
        return view('criminal-cases.admin-index', [
            'criminalCases' => $criminalCases
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
        return view('criminal-cases.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:criminal_cases,name'],
            'description' => ['required', 'string', 'max:500'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = Auth::id();
        $validated['hex'] = $generator->uniqueHexId();

        // Ensure the generated slug is unique
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:criminal_cases,slug'],
        ])->validate();

        CriminalCase::create($validated);

        return redirect( route('admin.criminal-cases.index') )->with('status', [
            'type' => 'success',
            'message' => 'Criminal Case added.'
        ]);

    }



    // -----------------------------------------------------
    // EDIT
    // -----------------------------------------------------
    
    public function edit(CriminalCase $criminalCase)
    {
        return view('criminal-cases.edit', compact('criminalCase'));
    }



    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    public function update(Request $request, CriminalCase $criminalCase)
    {   
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('criminal_cases', 'name')->ignore($criminalCase->id)],
            'description' => ['required', 'string', 'max:500'],
            'is_published' => ['required', 'boolean']
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure the generated slug is unique
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', Rule::unique('criminal_cases', 'slug')->ignore($criminalCase->id)],
        ])->validate();


        // Set published date if first publish

        if ($validated['is_published'] && ! $criminalCase->published_at) {
            $validated['published_at'] = now();
        }

        $criminalCase->update($validated);

        return redirect()
            ->route('admin.criminal-cases.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Criminal Case updated.',
            ]);
            
    }


    










     // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(CriminalCase $criminalCase)
    {
        foreach ($criminalCase->images as $image) {
            if ($image->path && Storage::disk('public')->exists($image->display_path)) {
                Storage::disk('public')->delete($image->display_path);
            }
        }

        $criminalCase->images()->delete();

        $criminalCase->delete();

        return redirect()
            ->route('admin.criminal-cases.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Criminal Case deleted.',
            ]);
    }









    // -----------------------------------------------------
    // IMAGES INDEX
    // -----------------------------------------------------

    public function imagesIndex(CriminalCase $criminalCase)
    {
        $images = $criminalCase->images()
            ->latest()
            ->paginate(10);

        return view('criminal-cases.images-index', [
            'criminalCase' => $criminalCase,
            'images' => $images,
        ]);
    }


    // -----------------------------------------------------
    // CREATE IMAGE
    // -----------------------------------------------------

    public function createImage(CriminalCase $criminalCase)
    {   
        return view('criminal-cases.create-image', [
            'criminalCase' => $criminalCase,
        ]);
    }


    // -----------------------------------------------------
    // STORE ARTICLE IMAGE
    // -----------------------------------------------------
    
    public function storeImage(Request $request, CriminalCase $criminalCase, ImageOptimizer $optimizer)
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
        $path = "criminal-cases/{$criminalCase->hex}/{$filename}";

        $manager = ImageManager::usingDriver(Driver::class);
        $decodedImage = $manager->decodeBinary($imageData);

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $decodedImage->encode(
                new JpegEncoder(quality: 90)
            )
        );

        if (! empty($validated['is_featured'])) {
            $criminalCase->images()->update([
                'is_featured' => false,
            ]);
        }

        $image = $criminalCase->images()->create([
            'image_path' => $path,
            'caption' => $validated['caption'],
            'alt_text' => $validated['alt_text'],
            'credit_name' => $validated['credit_name'],
            'credit_url' => $validated['credit_url'],
            'is_featured' => $validated['is_featured'] ?? false,
        ]);

        try {
            $optimizer->optimizeModel($image);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('admin.criminal-cases.images.index', $criminalCase)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded.',
            ]);
    }


    public function optimizeImage(CriminalCase $criminalCase, Image $image)
    {
        try {
            app(ImageOptimizer::class)->optimizeImage($image);

            return back()->with('status', [
                'type' => 'success',
                'message' => 'Image optimized.',
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

    public function editImage(CriminalCase $criminalCase, Image $image)
    {   
        // Header actions
        $title = 'Edit Image';
        $subtitle = 'Replace the image or update its details and metadata.';
        $actions = [
            'back' => [
                'label' => 'Back to Articles',
                'href' => route('admin.criminal-cases.index'),
                'variant' => 'ghost',
            ]
        ];

        return view('criminal-cases.edit-image', [
            'criminalCase' => $criminalCase,
            'image' => $image,
            'title' => $title,
            'subtitle' => $subtitle,
            'actions' => $actions
        ]);
    }


    // -----------------------------------------------------
    // UPDATE ARTICLE IMAGE
    // -----------------------------------------------------

    public function updateImage(Request $request, CriminalCase $criminalCase, Image $image)
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
            $path = "criminal-cases/{$filename}";

            Storage::disk('public')->put($path, $imageData);

            $image->path = $path;
        }

        // Only one featured image per article
        if ($request->boolean('is_featured')) {
            $criminalCase->images()
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
            ->route('admin.criminal-cases.images.index', $criminalCase)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image updated.',
            ]);
    }



    public function destroyImage(CriminalCase $criminalCase, Image $image)
    {
        $image = $criminalCase->images()
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
            ->route('admin.criminal-cases.images.index', $criminalCase)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image deleted.',
            ]);
    }
    
}
