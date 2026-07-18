<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Image;
use App\Models\Playlist;
use App\Models\Video;
use App\Services\ImageOptimizer;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;


class PlaylistController extends Controller
{
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index(Request $request)
    {
        $playlists = Playlist::query();

        //
        // Put filters here if required.
        //

        $playlists = $playlists->latest()->paginate();

       
                
        return view('playlists.admin-index', [
            'playlists' => $playlists            
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {
        $criminalCases = CriminalCase::orderBy('name')->get();
        
        return view('playlists.create', [
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
            'criminal_case_id' => ['nullable', 'exists:criminal_cases,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_published' => ['required', 'boolean']
        ]);


        // Ensure character name is unique for this criminal case

        $exists = Playlist::where('name', $request->name)
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
                    'name' => 'That playlist already exists in this case.',
                ])
                ->withInput();
        }


        
        $validated['user_id'] = Auth::id();
        $validated['hex'] = $generator->uniqueHexId();

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure the generated slug is unique
        
        validator($validated, [
            'slug' => ['required', 'string', 'max:100', 'unique:characters,slug'],
        ])->validate();


        // Create Character
        $playlist = Playlist::create($validated);
        
        $position = $playlist->criminalCases()->count() + 1;

        $playlist->criminalCases()->attach(
            $request->criminal_case_id,
            [
                'position' => $position,
            ]
        );

        return redirect()
            ->route('admin.playlists.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Playlist created.'
            ])
        ;
    }




    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Playlist $playlist)
    {
        foreach ($playlist->images as $image) {
            if ($image->path && Storage::disk('public')->exists($image->display_path)) {
                Storage::disk('public')->delete($image->display_path);
            }
        }

        $playlist->images()->delete();

        $playlist->delete();

        return redirect()
            ->route('admin.playlists.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Playlist deleted.',
            ]);
    }






    // -----------------------------------------------------
    // IMAGES INDEX
    // -----------------------------------------------------

    public function imagesIndex(Playlist $playlist)
    {

    
        $images = $playlist->images()
            ->latest()
            ->paginate();

        return view('playlists.images-index', [
            'character' => $playlist,
            'images' => $images,
        ]);
    }


    // -----------------------------------------------------
    // CREATE IMAGE
    // -----------------------------------------------------

    public function createImage(Playlist $playlist)
    {   
        return view('playlists.create-image', [
            'playlist' => $playlist,
        ]);
    }


    // -----------------------------------------------------
    // STORE ARTICLE IMAGE
    // -----------------------------------------------------
    
    public function storeImage(Request $request, Playlist $playlist, ImageOptimizer $optimizer)
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
        $path = "playlists/{$playlist->hex}/{$filename}";

        $manager = ImageManager::usingDriver(Driver::class);
        $decodedImage = $manager->decodeBinary($imageData);

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $decodedImage->encode(
                new JpegEncoder(quality: 90)
            )
        );

        if (! empty($validated['is_featured'])) {
            $playlist->images()->update([
                'is_featured' => false,
            ]);
        }

        $image = $playlist->images()->create([
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
            ->route('admin.playlists.images.index', $playlist)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded.',
            ]);
    }







    public function destroyImage(Playlist $playlist, Image $image)
    {
        $image = $playlist->images()
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
            ->route('admin.playlists.images.index', $playlist)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image deleted.',
            ]);
    }





    // -----------------------------------------------------
    // VIDEOS INDEX
    // -----------------------------------------------------

    public function videosIndex(Playlist $playlist)
    {

    
        $playlistVideos = $playlist->videos()
            ->orderBy('playlist_video.position')
            ->get()
        ;

        return view('playlists.videos-index', [
            'playlist' => $playlist,
            'playlistVideos' => $playlistVideos,
        ]);
    }


    // -----------------------------------------------------
    // CREATE VIDEO
    // -----------------------------------------------------

    // public function createVideo(Playlist $playlist)
    // {
    //     $videos = Video::query()
    //         ->paginate(25);

    //     return view(
    //         'playlists.create-video',
    //         compact('playlist', 'videos')
    //     );
    // }


    // -----------------------------------------------------
    // CREATE VIDEO
    // -----------------------------------------------------

    // public function storeVideo(
    //     Request $request,
    //     Playlist $playlist
    // ) {
    //     foreach ($request->videos as $videoId) {

    //         $position = ($playlist->videos()->max('position') ?? 0) + 1;

    //         $playlist->videos()->attach(
    //             $videoId,
    //             [
    //                 'position' => $position,
    //             ]
    //         );
    //     }

    //     return redirect()->route(
    //         'admin.playlists.videos.index',
    //         $playlist
    //     );
    // }

    

    // -----------------------------------------------------
    // EDIT VIDEOS
    // -----------------------------------------------------

    public function editVideos(Playlist $playlist)
    {
        return view('playlists.edit-videos', [
            'playlist' => $playlist,
            'availableVideos' => Video::whereDoesntHave('playlists', function ($query) use ($playlist) {
                $query->where('playlists.id', $playlist->id);
            })->orderBy('title')->get(),

            'playlistVideos' => $playlist->videos()
                ->orderBy('playlist_video.position')
                ->get(),
            ])
        ;
    }


    // -----------------------------------------------------
    // UPDATE VIDEOS
    // -----------------------------------------------------

    public function updateVideos(Request $request, Playlist $playlist)
    {
        $validated = $request->validate([
            'videos' => ['array'],
            'videos.*' => ['exists:videos,id'],
        ]);

        $sync = [];

        foreach ($validated['videos'] ?? [] as $position => $videoId) {
            $sync[$videoId] = [
                'position' => $position,
            ];
        }

        $playlist->videos()->sync($sync);

        return redirect()
            ->route('admin.playlists.videos.index', $playlist)
            ->with('status', [
                'type' => 'success',
                'message' => 'Playlist updated.',
            ]);
    }





}
