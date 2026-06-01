<?php

namespace App\Http\Controllers;

use App\Jobs\TranscriptionJob;
use App\Models\Transcription;
use Illuminate\Http\Request;



class TranscriptionController extends Controller
{
    public function index()
    {
        $transcriptions = auth()->user()
            ->transcriptions()
            ->latest()
            ->paginate(20);

        return view('admin.transcriptions-index', [
            'transcriptions' => $transcriptions
        ]);
    }

    public function create()
    {
        return view('admin.transcriptions-create');
    }

    public function store(Request $request)
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
            ->route('transcriptions.index')
            ->with('success', 'Your transcription has started.');
    }
}
