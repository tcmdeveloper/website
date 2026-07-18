<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Image;
use App\Models\Judge;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;



// -----------------------------------------------------
// JUDGE CONTROLLER (ADMIN)
// -----------------------------------------------------

class JudgeController extends Controller
{
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $judges = Judge::orderBy('first_name')->paginate();

        
        return view('judges.admin-index', [
            'judges' => $judges
        ]);
    }

    
    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
        return view('judges.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'court' => ['nullable', 'string', 'max:100'],
        ]);

        Judge::create($validated);

        return redirect()
            ->route('admin.judges.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'New Judge created.',
            ])
        ;

    }


    // -----------------------------------------------------
    // EDIT
    // -----------------------------------------------------

    public function edit(Judge $judge)
    {   
        $criminalCases = CriminalCase::orderBy('name')->pluck('name', 'id');

        return view('judges.edit', [
            'judge' => $judge,
            'criminalCases' => $criminalCases,
        ]);
    }


    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    public function update(Request $request, Judge $judge)
    {   
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'court' => ['nullable', 'string', 'max:100'],
           
        ]);


        $judge->update($data);

        return redirect()
            ->route('admin.judges.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Judge updated.',
            ]);
            
    }


     // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Judge $judge)
    {
        // foreach ($judge->images as $image) {
        //     if ($image->path && Storage::disk('public')->exists($image->display_path)) {
        //         Storage::disk('public')->delete($image->display_path);
        //     }
        // }

        // $article->images()->delete();

        $judge->delete();

        return redirect()
            ->route('admin.judges.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Judge deleted.',
            ]);
    }




    // -----------------------------------------------------
    // IMAGES INDEX
    // -----------------------------------------------------

    public function imagesIndex(Judge $judge)
    {
        $images = $judge->images()
            ->latest()
            ->paginate(10);

        return view('judges.images-index', [
            'judge' => $judge,
            'images' => $images,
        ]);
    }


    // -----------------------------------------------------
    // CREATE IMAGE
    // -----------------------------------------------------

    public function createImage(Judge $judge)
    {   
        return view('judges.create-image', [
            'judge' => $judge,
        ]);
    }


    // -----------------------------------------------------
    // STORE ARTICLE IMAGE
    // -----------------------------------------------------

    public function storeImage(Request $request, Judge $judge, ImageOptimizer $optimizer)
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
        $path = "judges/{$judge->id}/{$filename}";

        $manager = ImageManager::usingDriver(Driver::class);
        $decodedImage = $manager->decodeBinary($imageData);

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $decodedImage->encode(
                new JpegEncoder(quality: 90)
            )
        );

        if (! empty($validated['is_featured'])) {
            $judge->images()->update([
                'is_featured' => false,
            ]);
        }

        $image = $judge->images()->create([
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
            ->route('admin.judges.images.index', $judge)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded.',
            ]);
    }


    // -----------------------------------------------------
    // EDIT JUDGE IMAGE
    // -----------------------------------------------------

    public function editImage(Judge $judge, Image $image)
    {   
        // Header actions
        $title = 'Edit Image';
        $subtitle = 'Replace the image or update its details and metadata.';
        $actions = [
            'back' => [
                'label' => 'Back to Images',
                'href' => route('admin.judges.images.index', $judge),
                'variant' => 'ghost',
            ]
        ];

        return view('judges.edit-image', [
            'judge' => $judge,
            'image' => $image,
            'title' => $title,
            'subtitle' => $subtitle,
            'actions' => $actions
        ]);
    }


    // -----------------------------------------------------
    // UPDATE ARTICLE IMAGE
    // -----------------------------------------------------

    public function updateImage(Request $request, Judge $judge, Image $image)
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
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Save new image
            $filename = Str::uuid() . '.jpg';
            $path = "judges/{$filename}";

            Storage::disk('public')->put($path, $imageData);

            $image->image_path = $path;
        }

        // Only one featured image per article
        if ($request->boolean('is_featured')) {
            $judge->images()
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
            ->route('admin.judges.images.index', $judge)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image updated.',
            ]);
    }

}
