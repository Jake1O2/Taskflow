<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate([
            'name' => 'Free',
        ], [
            'name' => 'Free',
            'price' => 0,
            'stripe_price_id' => 'prod_TzbSYqiJGyMe2Z',
            'features' => ['tasks', 'comments', 'kanban', 'basic'],
            'max_projects' => 3,
            'max_teams' => 1,
        ]);

        Plan::updateOrCreate([
            'name' => 'Pro',
        ], [
            'name' => 'Pro',
            'price' => 2900, // $29.00
            'stripe_price_id' => 'prod_TzbZ1zs17Lx7G9', 
            'features' => ['all_features', 'export', 'notifications', 'api'],
            'max_projects' => null, // unlimited
            'max_teams' => 5,
        ]);

        Plan::updateOrCreate([
            'name' => 'Enterprise',
        ], [
            'name' => 'Enterprise',
            'price' => 9900, // $99.00
            'stripe_price_id' => 'prod_TzbeXOwzIJfLQM', 
            'features' => ['all_features', 'webhooks', 'priority_support', 'sso'],
            'max_projects' => null, // unlimited
            'max_teams' => null, // unlimited
        ]);
    }
}
