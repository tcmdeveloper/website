<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CriminalCase;
use App\Models\Timeline;
use Illuminate\Http\Request;


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
        

        return view('criminal-cases.show', [
            'criminalCase' => $criminalCase,
        ]);
    }


    // -----------------------------------------------------
    // SHOW
    // -----------------------------------------------------

    public function timelinesIndex(CriminalCase $criminalCase, Timeline $timeline)
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



        $heatmapData = $events
    ->groupBy(fn ($event) => $event->occurred_at->format('Y-m'))
    ->map(fn ($events, $month) => [
        'date' => \Carbon\Carbon::createFromFormat('Y-m', $month)
            ->format('M Y'),
        'count' => $events->count(),
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
    ->map(function ($yearEvents, $year) use ($months) {

        $counts = $yearEvents
            ->groupBy(fn ($event) => $event->occurred_at->format('M'))
            ->map->count();

        return [
            'name' => (string) $year,
            'data' => collect($months)
                ->map(fn ($month) => [
                    'x' => $month,
                    'y' => $counts[$month] ?? 0,
                ])
                ->values(),
        ];
    })
    ->values();

    // dd($monthl   yCounts);

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
    

}
