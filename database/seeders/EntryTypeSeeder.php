<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('entry_types')->insert([
            [
                'name' => 'debit'
            ],
            [
                'name' => 'credit'
            ],
        ]);
    }
}
