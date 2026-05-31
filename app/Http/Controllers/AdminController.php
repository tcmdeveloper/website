<?php

namespace App\Http\Controllers;

use App\Jobs\TranscribeVideoJob;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class AdminController extends Controller
{
    public function transcribeVideo(Request $request)
{
    $request->validate([
        'url' => 'required|url'
    ]);

    TranscribeVideoJob::dispatch($request->url);

    return response()->json([
        'status' => 'queued'
    ]);
}

}
