<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DownloadYoutubeVideo;
use App\Models\Video;
use App\Services\RandomStringGenerator;
use App\Services\YoutubeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class VideoController extends Controller
{

    // -----------------------------------------------------
    // ADMIN INDEX
    // -----------------------------------------------------

    public function index()
    {
        $videos = Video::latest()->paginate(10);

        return view('videos.admin-index', compact('videos'));
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------

    public function create()
    {
        return view('videos.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, YoutubeService $youtube, Request $request)
    {
        $data = $request->validate(
            [
                'youtube_url' => [
                    'required',
                    'url',
                    'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i',
                ],
            ],
            [
                'youtube_url.regex' => 'Please enter a valid YouTube URL.',
            ]
        );

        $videoId = $youtube->id($request->youtube_url);

        $video = Video::create([
            'hex' => $generator->uniqueHexId(),
            'youtube_url' => $data['youtube_url'],
            'youtube_id' => $videoId,
            'status' => 'pending',
        ]);

        DownloadYoutubeVideo::dispatch($video);

        return redirect()
            ->route('admin.videos.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Video download started!',
            ]);
    }







    // -----------------------------------------------------
    // DESTROY
    // -----------------------------------------------------

    public function destroy(Video $video)
    {
        // 1. Delete video file (if exists)
        if ($video->filename && Storage::disk('public')->exists($video->filename)) {
            Storage::disk('public')->delete($video->filename);
        }

        // 2. Delete thumbnail file (if exists)
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        // 3. Delete DB record
        $video->delete();

        return redirect()
            ->route('admin.videos.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Video deleted successfully.',
            ]);
    }

    


}
