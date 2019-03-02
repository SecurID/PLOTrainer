<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->insert([
            'name' => 'Raise',
        ]);
        DB::table('actions')->insert([
            'name' => 'Call',
        ]);
        DB::table('actions')->insert([
            'name' => 'Fold',
        ]);
    }
}
