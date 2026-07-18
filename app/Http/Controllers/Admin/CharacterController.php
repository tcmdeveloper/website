<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Character;
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
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class CharacterController extends Controller
{
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index(Request $request)
    {
        $characters = Character::query();

        //
        // Put filters here if required.
        //

        $characters = $characters->latest()->paginate();

       
                
        return view('characters.admin-index', [
            'characters' => $characters            
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
         $criminalCases = CriminalCase::orderBy('name')->get();
        return view('characters.create', [
            'criminalCases' => $criminalCases
        ]);
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'criminal_case_id' => ['required', 'exists:criminal_cases,id'],
            'type' => ['required'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            'date_of_death' => [
                'nullable',
                'date',
                'after:date_of_birth',
                'before_or_equal:today',
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['required', 'boolean']
        ]);


        // Ensure character name is unique for this criminal case

        $exists = Character::where('name', $request->name)
            ->whereHas('criminalCases', function ($query) use ($request) {
                $query->where(
                    'criminal_cases.id',
                    $request->criminal_case_id
                );
            })
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'name' => 'That character already exists in this case.',
                ])
                ->withInput();
        }


        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = Auth::id();
        $validated['hex'] = $generator->uniqueHexId();

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }



        // Ensure the generated slug is unique
        
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:characters,slug'],
        ])->validate();


        // Create Character
        $character = Character::create($validated);


        $character->criminalCases()->attach(
            $request->criminal_case_id,
            [
                'role' => $request->type,
            ]
        );

        return redirect()
            ->route('admin.characters.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Character created.'
            ])
        ;
    }




    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Character $character)
    {
        foreach ($character->images as $image) {
            if ($image->path && Storage::disk('public')->exists($image->display_path)) {
                Storage::disk('public')->delete($image->display_path);
            }
        }

        $character->images()->delete();

        $character->delete();

        return redirect()
            ->route('admin.characters.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Character deleted.',
            ]);
    }






    // -----------------------------------------------------
    // IMAGES INDEX
    // -----------------------------------------------------

    public function imagesIndex(Character $character)
    {

    
        $images = $character->images()
            ->latest()
            ->paginate();

        return view('characters.images-index', [
            'character' => $character,
            'images' => $images,
        ]);
    }


    // -----------------------------------------------------
    // CREATE IMAGE
    // -----------------------------------------------------

    public function createImage(Character $character)
    {   
        return view('characters.create-image', [
            'character' => $character,
        ]);
    }


    // -----------------------------------------------------
    // STORE ARTICLE IMAGE
    // -----------------------------------------------------
    
    public function storeImage(Request $request, Character $character, ImageOptimizer $optimizer)
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
        $path = "characters/{$character->hex}/{$filename}";

        $manager = ImageManager::usingDriver(Driver::class);
        $decodedImage = $manager->decodeBinary($imageData);

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $decodedImage->encode(
                new JpegEncoder(quality: 90)
            )
        );

        if (! empty($validated['is_featured'])) {
            $character->images()->update([
                'is_featured' => false,
            ]);
        }

        $image = $character->images()->create([
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
            ->route('admin.characters.images.index', $character)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded.',
            ]);
    }







    public function destroyImage(Character $character, Image $image)
    {
        $image = $character->images()
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
            ->route('admin.characters.images.index', $character)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image deleted.',
            ]);
    }


}
