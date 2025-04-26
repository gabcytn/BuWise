<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            [
                'description' => 'pending'
            ],
            [
                'description' => 'verified'
            ],
            [
                'description' => 'rejected'
            ]
        ]);
    }
}
