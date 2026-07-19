<?php

namespace Database\Seeders;

use App\Models\TimelineEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TimelineEventSeeder extends Seeder
{
    public function run(): void
    {

        $events = [

            [
                'title' => 'Murder of Dan Markel',
                'description' => 'Florida State law professor Dan Markel was shot in the garage of his Tallahassee home.',
                'occurred_at' => '2014-07-18 11:00:00',
                'type' => 'murder',
                'icon' => 'skull',
                'color' => 'red',
                'source_name' => 'Court records',
            ],

            [
                'title' => 'Garcia and Rivera arrested',
                'description' => 'Luis Rivera and Sigfredo Garcia were arrested in connection with Dan Markel’s murder.',
                'occurred_at' => '2016-05-26 09:00:00',
                'type' => 'arrest',
                'icon' => 'handcuffs',
                'color' => 'orange',
            ],

            [
                'title' => 'Katherine Magbanua arrested',
                'description' => 'Katherine Magbanua was arrested and accused of helping arrange the murder.',
                'occurred_at' => '2016-10-01 09:00:00',
                'date_label' => 'October 2016',
                'type' => 'arrest',
                'icon' => 'handcuffs',
                'color' => 'orange',
            ],

            [
                'title' => 'First Magbanua trial ends in mistrial',
                'description' => 'Jurors were unable to reach a unanimous verdict in Katherine Magbanua’s first trial.',
                'occurred_at' => '2019-09-27 17:00:00',
                'type' => 'trial',
                'icon' => 'gavel',
                'color' => 'yellow',
            ],

            [
                'title' => 'Magbanua convicted',
                'description' => 'Katherine Magbanua was convicted of first-degree murder, conspiracy, and solicitation.',
                'occurred_at' => '2022-05-19 16:00:00',
                'type' => 'verdict',
                'icon' => 'gavel',
                'color' => 'green',
            ],

            [
                'title' => 'Donna Adelson arrested',
                'description' => 'Donna Adelson was arrested at Miami International Airport while attempting to leave the country.',
                'occurred_at' => '2023-11-13 19:00:00',
                'type' => 'arrest',
                'icon' => 'handcuffs',
                'color' => 'orange',
            ],

            [
                'title' => 'Charlie Adelson arrested',
                'description' => 'Charlie Adelson was arrested and charged with first-degree murder, conspiracy, and solicitation.',
                'occurred_at' => '2022-04-21 10:00:00',
                'type' => 'arrest',
                'icon' => 'handcuffs',
                'color' => 'orange',
            ],

            [
                'title' => 'Charlie Adelson trial begins',
                'description' => 'Jury selection and opening statements began in Charlie Adelson’s murder trial.',
                'occurred_at' => '2023-10-23 09:00:00',
                'type' => 'trial',
                'icon' => 'gavel',
                'color' => 'blue',
            ],

            [
                'title' => 'Charlie Adelson found guilty',
                'description' => 'A jury found Charlie Adelson guilty of first-degree murder, conspiracy, and solicitation.',
                'occurred_at' => '2023-11-06 17:00:00',
                'type' => 'verdict',
                'icon' => 'scale',
                'color' => 'green',
            ],

            [
                'title' => 'Charlie Adelson sentenced',
                'description' => 'Charlie Adelson was sentenced to life in prison without parole.',
                'occurred_at' => '2023-12-12 10:00:00',
                'type' => 'sentencing',
                'icon' => 'gavel',
                'color' => 'purple',
            ],

            [
                'title' => 'Charlie Adelson files appeal',
                'description' => 'Charlie Adelson appealed his conviction and sentence.',
                'occurred_at' => '2024-01-01 09:00:00',
                'date_label' => 'Early 2024',
                'type' => 'appeal',
                'icon' => 'document',
                'color' => 'gray',
            ],

        ];

        foreach ($events as $index => $event) {

            TimelineEvent::create([
                'hex' => strtoupper(Str::random(11)),

                'timeline_id' => 1,
                'parent_event_id' => null,

                'title' => $event['title'],
                'description' => $event['description'] ?? null,

                'occurred_at' => isset($event['occurred_at'])
                    ? Carbon::parse($event['occurred_at'])
                    : null,

                'date_label' => $event['date_label'] ?? null,
                'time_label' => $event['time_label'] ?? null,

                'sort_order' => $index + 1,

                'type' => $event['type'] ?? null,
                'icon' => $event['icon'] ?? null,
                'color' => $event['color'] ?? null,

                'source_name' => $event['source_name'] ?? null,
                'source_url' => $event['source_url'] ?? null,

                'is_published' => true,
                'published_at' => now(),
            ]);
        }
    }
}
