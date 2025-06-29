<?php

namespace Database\Seeders;

use App\Models\AccountGroup;
use App\Models\LedgerAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateWithholdingTaxAccount extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ledger_accounts')
            ->where('code', '=', 107)
            ->limit(1)
            ->update(['code' => 108]);
        DB::table('ledger_accounts')
            ->where('code', '=', 106)
            ->limit(1)
            ->update(['code' => 107]);

        LedgerAccount::create([
            'code' => 106,
            'account_group_id' => AccountGroup::ASSETS,
            'type' => 'receivable',
            'name' => 'Withholding Tax Payable',
        ]);
    }
}
