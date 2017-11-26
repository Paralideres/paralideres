<?php

use Illuminate\Database\Seeder;

class PollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Poll::class, 10)->create();
        factory(App\Models\PollOption::class, 50)->create();
    }
}
