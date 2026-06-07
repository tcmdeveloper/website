<?php

namespace App\Http\Controllers;

use App\Jobs\TranscriptionJob;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;


// -----------------------------------------------------
// ADMIN CONTROLLER (ADMIN)
// -----------------------------------------------------

class AdminController extends Controller
{

    // -----------------------------------------------------
    // TRANSCRIBE VIDEO
    // -----------------------------------------------------

    public function transcribeVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file'
        ]);

        TranscriptionJob::dispatch($request->url);

        return response()->json([
            'status' => 'queued'
        ]);
    }

    // -----------------------------------------------------
    // TRANSLATE SUBTITLES
    // -----------------------------------------------------

    public function translateSubtitles()
    {
        // $audioPath = storage_path('app/audio/input.mp3');
        $outputDir = storage_path('app/subtitles');

        // 1. Run WhisperX (JSON output)
        // $whisper = new Process([
        //     '/opt/homebrew/opt/python@3.11/bin/python3.11',
        //     '-m',
        //     'whisperx',
        //     $audioPath,
        //     '--model',
        //     'large-v3',
        //     '--output_format',
        //     'json',
        //     '--output_dir',
        //     $outputDir,
        // ]);

        // $whisper->mustRun();

        // assume whisper produces:
        $jsonPath = $outputDir . '/audio_6a1cb1aceec8d.json';
        $srtPath  = $outputDir . '/input-short.srt';

        // 2. Convert JSON → SRT with formatting rules
        $python = '/opt/homebrew/opt/python@3.11/bin/python3.11';

        $script = app_path('Services/Transcription/json_to_srt.py');

        $convert = new Process([
            $python,
            $script,
            $jsonPath,
            $srtPath,
            20,
            1
        ]);

        $convert->mustRun();

        return response()->download($srtPath);
    }

}
