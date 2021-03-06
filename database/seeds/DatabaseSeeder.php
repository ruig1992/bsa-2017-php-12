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
        $this->call(UsersTableSeeder::class);
        $this->call(UsersAdminTableSeeder::class);
        $this->call(UsersCarsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
    }
}
