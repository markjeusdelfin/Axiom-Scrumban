<?php

namespace Database\Seeders;

use App\Models\Tasks;
use App\Models\User;
use App\Models\Sprints;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $orgId = Str::uuid();
        
        // Get the current active sprint
        $sprint = Sprints::where('status', 'active')->first();
        if (!$sprint) {
            return;
        }

        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $taskTitles = [
            'Implement user authentication',
            'Design dashboard mockups',
            'Fix navigation bugs',
            'Write unit tests for API',
            'Optimize database queries',
            'Create user documentation',
            'Set up CI/CD pipeline',
            'Review pull requests',
            'Update API endpoints',
            'Implement caching layer',
            'Fix CSS styling issues',
            'Create REST API documentation',
            'Refactor legacy code',
            'Set up monitoring alerts',
            'Prepare deployment scripts',
            'Review security vulnerabilities',
            'Implement search functionality',
            'Create backup strategy',
            'Optimize image loading',
            'Write integration tests',
        ];

        $descriptions = [
            'This task involves implementing core functionality.',
            'High priority item that blocks other work.',
            'Bug fix for production issue.',
            'Feature enhancement requested by stakeholders.',
            'Technical debt that should be addressed.',
            'Documentation and knowledge transfer.',
            'Infrastructure and DevOps work.',
            'Code review and quality assurance.',
            'Performance optimization task.',
            'Testing and validation work.',
        ];

        $priorities = ['high', 'medium', 'low'];
        $statuses = ['todo', 'progress', 'done'];

        // Task distribution - some users get more tasks than others
        $taskDistribution = [
            1 => 3,  // Alex Rivera - light load
            2 => 9,  // Sam Chen - overloaded (90%+ utilization)
            3 => 5,  // Jordan Smith - moderate load
            4 => 2,  // Casey Morgan - designer, fewer tasks
            5 => 2,  // Parker Lee - QA, fewer tasks
        ];

        $taskPosition = 0;

        foreach ($users as $user) {
            $count = $taskDistribution[$user->id] ?? 3;

            for ($i = 0; $i < $count; $i++) {
                $priority = $priorities[array_rand($priorities)];
                $status = $statuses[array_rand($statuses)];

                Tasks::create([
                    'id' => Str::uuid(),
                    'column_id' => Str::uuid(),
                    'organization_id' => $orgId,
                    'sprint_id' => $sprint->id,
                    'user_id' => $user->id,
                    'title' => $taskTitles[array_rand($taskTitles)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'position' => $taskPosition++,
                    'metadata' => [
                        'priority' => $priority,
                        'status' => $status,
                        'created_by' => 'Seeder',
                        'tags' => ['feature', 'backend', 'frontend'][array_rand(['feature', 'backend', 'frontend'])],
                    ],
                ]);
            }
        }
    }
}
