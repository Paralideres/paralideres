<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Users
        $this->call(UsersTableSeeder::class);

        // Categories
        $this->call(CategoryTableSeeder::class);

        // Roles
        $this->call(RolesAndPermissionsSeeder::class);

        // Default Factory
        factory(App\Models\User::class, 10)->create();
        factory(App\Models\Category::class, 5)->create();
        factory(App\Models\Collection::class, 50)->create();
        factory(App\Models\Resource::class, 100)->create();
    }
}
