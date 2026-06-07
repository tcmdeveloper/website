<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Jobs\TranscriptionJob;
use App\Models\Transcription;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;


// -----------------------------------------------------
// TRANSCRIPTION CONTROLER (ADMIN)
// -----------------------------------------------------

class TranscriptionController extends Controller
{   

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $transcriptions = auth()->user()
            ->transcriptions()
            ->latest()
            ->paginate(20);

        return view('admin.transcriptions.index', [
            'transcriptions' => $transcriptions
        ]);
    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------

    public function create()
    {
        return view('admin.transcriptions.create');
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'youtube_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'max:512000'], // ~500MB
        ]);

        if (!$validated['youtube_url'] && !$request->hasFile('file')) {
            return back()->withErrors([
                'source' => 'Please provide either a YouTube URL or a file.'
            ]);
        }

        $sourceType = $validated['youtube_url'] ? 'youtube' : 'upload';
        $sourceUrl = $validated['youtube_url'] ?? null;

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('transcriptions');
        }

        $transcription = Transcription::create([
            'hex' => $generator->uniqueHexId(),
            'user_id' => auth()->id(),
            'source_type' => $sourceType,
            'source_url' => $sourceUrl,
            'file_path' => $filePath,
            'status' => 'pending',
            'progress' => 0,
        ]);

        // 🚀 VERY IMPORTANT: dispatch processing job
        TranscriptionJob::dispatch($transcription);

        return redirect()
            ->route('admin.transcriptions.index')
            ->with('success', 'Your transcription has started.');
    }

}
