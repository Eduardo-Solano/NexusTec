<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use App\Models\Criterion;
use App\Models\Evaluation;
use App\Models\StudentProfile;
use App\Models\Career;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $criterios = Criterion::all();
        $careers = Career::all();
        $advisors = User::role('advisor')->get();
        $judges = User::role('judge')->get();

        $activeEvents = Event::factory(5)->create([
            'status' => Event::STATUS_ACTIVE,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(5),
        ]);

        foreach ($activeEvents as $event) {
            $judgesIds = \App\Models\JudgeProfile::whereIn('user_id', $judges->pluck('id'))->pluck('id');
            
            $testJudge = User::where('email', 'juez@nexustec.com')->first();
            $testJudgeProfileId = $testJudge?->judgeProfile?->id;

            $selectedJudges = collect();
            
            if ($judgesIds->isNotEmpty()) {
                $selectedJudges = $judgesIds->random(min($judgesIds->count(), rand(3, 5)));
            }

            if ($testJudgeProfileId) {
                $selectedJudges->push($testJudgeProfileId);
            }

            if ($selectedJudges->isNotEmpty()) {
                $event->judges()->syncWithoutDetaching($selectedJudges->unique());
            }

            for ($i = 0; $i < 6; $i++) {
                $this->createCompleteTeam($event, $careers, $advisors, $judges, $criterios, false);
            }
        }
    }

    private function createCompleteTeam($event, $careers, $advisors, $judges, $criterios, $isPast)
    {
        $leader = User::factory()->create();
        $leader->assignRole('student');
        StudentProfile::factory()->create(['user_id' => $leader->id, 'career_id' => $careers->random()->id]);

        $advisor = (rand(1, 100) <= 70) ? $advisors->random() : null;
        $advisorStatus = ($advisor && $isPast) ? 'accepted' : 'pending';

        $team = Team::factory()->create([
            'event_id' => $event->id,
            'leader_id' => $leader->id,
            'advisor_id' => $advisor?->id,
            'advisor_status' => $advisorStatus,
        ]);

        $team->users()->attach($leader->id, [
            'role' => 'leader',
            'is_accepted' => true,
            'requested_by_user' => true
        ]);

        $membersCount = rand(2, 3);
        $members = User::factory($membersCount)->create();
        
        foreach ($members as $member) {
            $member->assignRole('student');
            StudentProfile::factory()->create(['user_id' => $member->id, 'career_id' => $careers->random()->id]);
            
            $team->users()->attach($member->id, [
                'role' => 'member',
                'is_accepted' => true,
                'requested_by_user' => false
            ]);
        }

        $project = Project::factory()->create([
            'team_id' => $team->id,
            'repository_url' => $isPast ? 'https://github.com/nexustec/repo-prueba' : null,
        ]);
    }
}