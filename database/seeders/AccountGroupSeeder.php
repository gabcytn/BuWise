<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('account_groups')->insert([
            [
                'name' => 'Assets',
            ],
            [
                'name' => 'Liabilities',
            ],
            [
                'name' => 'Equity',
            ],
            [
                'name' => 'Revenue',
            ],
            [
                'name' => 'Expenses',
            ],
        ]);
    }
}
