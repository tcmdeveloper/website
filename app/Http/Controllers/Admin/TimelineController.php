<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TimelineType;
use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Timeline;
use App\Models\TimelineEvent;
use App\Services\RandomStringGenerator;
use App\Support\DateTimeCombiner;
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
    public function storeEvent(RandomStringGenerator $generator, DateTimeCombiner $dateTime, Request $request, Timeline $timeline)
    {
        $validated = $request->validate([
            'timeline_id' => ['required', 'integer', 'exists:timelines,id'],
            'title' => ['required', 'string', 'max:150'],
            'type' => ['nullable', 'string'],
            'occurred_at_date' => ['nullable', 'date'],
            'occurred_at_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['hex'] = $generator->uniqueHexId();
        $validated['user_id'] = auth()->id();
        $validated['occurred_at'] = $dateTime->combine(
            $validated['occurred_at_date'],
            $validated['occurred_at_time']
        );
        $validated['is_published'] = true;
        $validated['published_at'] = now();
        // $validated['is_published'] = $validated['is_published'];
        // $validated['published_at'] = $validated['is_published'] ? now() : null;

        TimelineEvent::create($validated);

        return redirect()
            ->route('admin.timelines.events.index', $timeline)
            ->with('status', [
                'type' => 'success',
                'message' => 'Event saved to timeline.',
            ]);
    }


    // -----------------------------------------------------
    // EDIT EVENT
    // -----------------------------------------------------

    public function editEvent(Timeline $timeline, TimelineEvent $event)
    {   
        $timelines = Timeline::orderBy('name')->pluck('name', 'id');

        return view('timelines.edit-event', [
            'timelines' => $timelines,
            'timeline' => $timeline,
            'event' => $event,
        ]);
    }


    // -----------------------------------------------------
    // UPDATE ARTICLE IMAGE
    // -----------------------------------------------------

    public function updateEvent(Request $request, DateTimeCombiner $dateTime, Timeline $timeline, TimelineEvent $event)
    {

        $validated = $request->validate([
            'timeline_id' => ['required', 'integer', 'exists:timelines,id'],
            'title' => ['required', 'string', 'max:150'],
            'type' => ['nullable', 'string'],
            'occurred_at_date' => ['nullable', 'date'],
            'occurred_at_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['occurred_at'] = $dateTime->combine(
            $validated['occurred_at_date'],
            $validated['occurred_at_time']
        );
        

        // Set published date if first publish

        // if ($event->is_published && ! $event->published_at) {
        //     $validated['published_at'] = now();
        // }

        $event->update($validated);

        return redirect()
            ->route('admin.timelines.events.index', $timeline)
            ->with('status', [
                'type' => 'success',
                'message' => 'Event updated.',
            ]);
    }


    // -----------------------------------------------------
    // DESTROY EVENT
    // -----------------------------------------------------

    public function destroyEvent(Timeline $timeline, TimelineEvent $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.timelines.events.index', $timeline)
            ->with('status', [
                'type' => 'success',
                'message' => 'Event deleted.',
            ]);
    }

    
}
