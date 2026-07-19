<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Document;
use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


// -----------------------------------------------------
// CRIMINAL CASE CONTROLLER (FRONT-END)
// -----------------------------------------------------

class CriminalCaseController extends Controller
{

    // -----------------------------------------------------
    // INDEX
    // -----------------------------------------------------

    public function index()
    {
        $criminalCases = CriminalCase::query()
            ->published()
            ->latestFirst()
            ->withCount('documents')
            ->paginate(12);
        
        return view('criminal-cases.index', [
            'criminalCases' => $criminalCases
        ]);
    }


    // -----------------------------------------------------
    // SHOW
    // -----------------------------------------------------

    public function show(CriminalCase $criminalCase)
    {   
        $criminalCase->load('documents.coverPage');
        $criminalCase->increment('views');

        $documents = Document::where('criminal_case_id', $criminalCase->id)
            ->latest()
            ->take(4)
            ->get()
        ;
        
        return view('criminal-cases.show', [
            'criminalCase' => $criminalCase,
            'documents' => $documents,
        ]);
    }


    // -----------------------------------------------------
    // TIMELINE EVENTS INDEX
    // -----------------------------------------------------

    public function timelineEventsIndex(CriminalCase $criminalCase, Timeline $timeline)
    {
        $timeline->increment('views');
        $start = $timeline->events->min('occurred_at');
        $end = $timeline->events->max('occurred_at');

        $totalDays = $start->diffInDays($end);

        $events = $timeline->events
            ->whereNull('parent_event_id')
            ->sortBy('occurred_at')
            ->values()
            ->map(function ($event) use ($start, $totalDays) {

                $days = $start->diffInDays($event->occurred_at);

                $event->percent = $totalDays
                    ? ($days / $totalDays) * 100
                    : 50;

                return $event;
            });


        $dailyEvents = $events
            ->groupBy(fn ($event) => $event->occurred_at->toDateString())
            ->map(function ($events, $date) {
                return [
                    'date' => $date,
                    'count' => $events->count(),
                    'events' => $events,
                ];
            })
            ->values();



        $yearGroups = $dailyEvents
            ->groupBy(fn ($item) => substr($item['date'], 0, 4))
            ->map(fn ($items, $year) => [
                'title' => $year,
                'cols' => $items->count(),
            ])
             ->values();



        $months = [
            'Jan', 'Feb', 'Mar', 'Apr',
            'May', 'Jun', 'Jul', 'Aug',
            'Sep', 'Oct', 'Nov', 'Dec',
        ];




        $monthlyCounts = $events
            ->groupBy(fn ($event) => $event->occurred_at->format('Y'))
            ->sortKeysDesc(SORT_NUMERIC)
            ->map(function ($yearEvents, $year) use (
                $months,
                $criminalCase,
                $timeline            
            ) {

                $counts = $yearEvents
                    ->groupBy(fn ($event) => $event->occurred_at->format('M'))
                    ->map->count();

                return [
                    'name' => (string) $year,
                    'data' => collect($months)
                        ->map(fn ($month) => [
                            'x' => $month,
                            'y' => $counts[$month] ?? 0,

                            'url' => route('cases.timeline.events', [
                                'criminalCase' => $criminalCase,
                                'timeline' => $timeline,
                                'year' => $year,
                                'month' => $month,
                            ]),
                        ])
                    ->values(),
                ];

            })

        ->values();


        $heatmapData = $monthlyCounts;


        return view('timeline-events.index', compact(
            'criminalCase',
            'timeline',
            'events',
            'dailyEvents',
            'yearGroups',
            'heatmapData'
        ));


    }


    // -----------------------------------------------------
    // TIMELINE EVENTS
    // -----------------------------------------------------


    public function timelineEvents(
        CriminalCase $criminalCase,
        Timeline $timeline,
        Request $request
    ) {
        $month = Carbon::createFromFormat(
            'M',
            $request->month
        )->month;

        $events = $timeline->events()
            ->whereNull('parent_event_id')
            ->whereYear('occurred_at', $request->year)
            ->whereMonth('occurred_at', $month)
            ->orderBy('occurred_at')
            ->get();

        return response()->json([
            'html' => view(
                'timeline-events.partials.events',
                compact('events')
            )->render(),
        ]);
    }
    

}
