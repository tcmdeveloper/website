<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TimelineType;
use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Timeline;
use App\Services\RandomStringGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TimelineController extends Controller
{
    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index(Request $request)
    {
        $timelines = Timeline::query();

        //
        // Put filters here if required.
        //

        $timelines = $timelines->latest()->paginate();
        
        return view('timelines.admin-index', [
            'timelines' => $timelines
        ]);

    }


    // -----------------------------------------------------
    // CREATE
    // -----------------------------------------------------
    
    public function create()
    {

        $criminalCases = CriminalCase::orderBy('name')->pluck('name', 'id');

        return view('timelines.create', [
            'criminalCases' => $criminalCases,
         ]);
    }


    // -----------------------------------------------------
    // STORE
    // -----------------------------------------------------

    public function store(RandomStringGenerator $generator, Request $request)
    {
        $validated = $request->validate([
            'criminal_case_id' => ['required', 'integer', 'exists:criminal_cases,id'],
            'name' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('timelines', 'name')
                    ->where(fn ($query) => $query->where('criminal_case_id', $request->criminal_case_id)),
            ],
            'slug' => [
                'required',
                'string',
                'max:100',
                Rule::unique('timelines', 'slug')
                    ->where(fn ($query) => $query->where('criminal_case_id', $request->criminal_case_id)),
            ],
            'type' => ['required', new Enum(TimelineType::class)],
            'description' => ['required', 'string', 'max:300'],
            'is_published' => ['required', 'boolean']
        ]);


        $validated['hex'] = $generator->uniqueHexId();
        $validated['user_id'] = auth()->id();
        $validated['published_at'] = $request->boolean('is_published') ? now() : null;


        Timeline::create($validated);


        return redirect( route('admin.timelines.events.index') )->with('status', [
            'type' => 'status',
            'message' => 'New timeline added.'
        ]);

    }


    // -----------------------------------------------------
    // EDIT
    // -----------------------------------------------------
    
    public function edit(Timeline $timeline)
    {
        $criminalCases = CriminalCase::orderBy('name')->pluck('name', 'id');
        return view('timelines.edit', compact('timeline', 'criminalCases'));
    }


    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

    public function update(Request $request, Timeline $timeline)
    {   
        $validated = $request->validate([
            'criminal_case_id' => ['required', 'integer', 'exists:criminal_cases,id'],
            'name' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('timelines', 'name')
                    ->where(fn ($query) => $query->where('criminal_case_id', $request->criminal_case_id))
                    ->ignore($timeline->id),
            ],
            'slug' => [
                'required',
                'string',
                'max:100',
                Rule::unique('timelines', 'slug')
                    ->where(fn ($query) => $query->where('criminal_case_id', $request->criminal_case_id))
                    ->ignore($timeline->id),
            ],
            'type' => ['required', new Enum(TimelineType::class)],
            'description' => ['required', 'string', 'max:300'],
            'is_published' => ['required', 'boolean']
        ]);


        // Set published date if first publish

        if ($validated['is_published'] && ! $timeline->published_at) {
            $validated['published_at'] = now();
        }

        $timeline->update($validated);

        return redirect()
            ->route('admin.timelines.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Timeline updated.',
            ]);
            
    }











    // -----------------------------------------------------
    // EVENTS INDEX
    // -----------------------------------------------------

    public function eventsIndex(Timeline $timeline)
    {
        $events = $timeline->events()
            ->latest()
            ->paginate();

        return view('timelines.events-index', [
            'timeline' => $timeline,
            'events' => $events,
        ]);
    }


    // -----------------------------------------------------
    // CREATE EVENT
    // -----------------------------------------------------

    public function createEvent(Timeline $timeline)
    {   
        $timelines = Timeline::orderBy('name')->pluck('name', 'id');

        return view('timelines.create-event', [
            'timelines' => $timelines,
            'timeline' => $timeline,
        ]);
    }


    // -----------------------------------------------------
    // STORE EVENT
    // -----------------------------------------------------
    public function storeImage(Request $request, Timeline $timeline)
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


        // Upload images to article.hex directory

        $filename = (string) Str::uuid();
        $path = "articles/{$article->hex}/{$filename}";

        $manager = ImageManager::usingDriver(Driver::class);
        $image = $manager->decodeBinary($imageData);


        Storage::disk('public')->put(
            "{$path}.avif",
            (string) $image->encode(new AvifEncoder(quality: 80))
        );

        Storage::disk('public')->put(
            "{$path}.webp",
            (string) $image->encode(new WebpEncoder(quality: 85))
        );

        Storage::disk('public')->put(
            "{$path}.jpg",
            (string) $image->encode(new JpegEncoder(quality: 90))
        );


        // If this image is becoming featured, clear any existing featured image.
        if (!empty($validated['is_featured'])) {
            $article->images()->update([
                'is_featured' => false,
            ]);
        }


        $article->images()->create([
            'image_path'   => $path,
            'caption'      => $validated['caption'],
            'alt_text'     => $validated['alt_text'],
            'credit_name'  => $validated['credit_name'],
            'credit_url'   => $validated['credit_url'],
            'is_featured'  => $validated['is_featured'] ?? false,
        ]);


        return redirect()
            ->route('admin.articles.images', $article)
            ->with('status', [
                'type' => 'success',
                'message' => 'Image uploaded successfully!',
            ]);
    }

    
}
