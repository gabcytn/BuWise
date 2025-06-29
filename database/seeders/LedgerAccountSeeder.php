<?php

namespace Database\Seeders;

use App\Models\AccountGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LedgerAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ledger_accounts')->insert([
            // Assets - Current Assets
            ['code' => 100, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash', 'type' => 'cash'],
            ['code' => 101, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash in Bank (Checking Account)', 'type' => 'cash'],
            ['code' => 102, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash in Bank (Savings Account)', 'type' => 'cash'],
            ['code' => 103, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Petty Cash', 'type' => 'cash'],
            ['code' => 104, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Accounts Receivable', 'type' => 'receivable'],
            ['code' => 105, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Input VAT Receivable', 'type' => 'receivable'],
            ['code' => 106, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Inventory', 'type' => null],
            ['code' => 107, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Prepaid Expenses', 'type' => null],
            // Assets - Fixed Assets
            ['code' => 150, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Land', 'type' => null],
            ['code' => 151, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Buildings', 'type' => null],
            ['code' => 152, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Vehicles', 'type' => null],
            ['code' => 153, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Equipment', 'type' => null],
            ['code' => 154, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Furniture and Fixtures', 'type' => null],
            // Assets - Other Assets
            ['code' => 170, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Investments', 'type' => null],
            ['code' => 171, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Goodwill', 'type' => null],
            ['code' => 172, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Intangible Assets', 'type' => null],
            // Liabilities - Current
            ['code' => 200, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Accounts Payable', 'type' => 'payable'],
            ['code' => 201, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Credit Card Payable', 'type' => 'payable'],
            ['code' => 202, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Wages Payable', 'type' => 'payable'],
            ['code' => 203, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Taxes Payable', 'type' => 'payable'],
            ['code' => 204, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Output VAT Payable', 'type' => 'payable'],
            ['code' => 205, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Short-Term Loans', 'type' => null],
            ['code' => 206, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Customer Deposits', 'type' => null],
            // Liabilities - Long Term
            ['code' => 250, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Mortgage Payable', 'type' => 'payable'],
            ['code' => 251, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Long-Term Loans', 'type' => null],
            ['code' => 252, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Bonds Payable', 'type' => 'payable'],
            // Equity
            ['code' => 300, 'account_group_id' => AccountGroup::EQUITY, 'name' => "Owner's Capital", 'type' => null],
            ['code' => 301, 'account_group_id' => AccountGroup::EQUITY, 'name' => "Owner's Drawings", 'type' => null],
            ['code' => 310, 'account_group_id' => AccountGroup::EQUITY, 'name' => 'Retained Earnings', 'type' => null],
            // REVENUE ACCOUNTS (401-499)
            ['code' => 400, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales', 'type' => null],
            ['code' => 401, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales Returns and Allowances', 'type' => null],
            ['code' => 402, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales Discounts', 'type' => null],
            ['code' => 403, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Commissions Earned', 'type' => null],
            ['code' => 405, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Rent Revenue', 'type' => null],
            ['code' => 410, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Other Income', 'type' => null],
            // EXPENSE ACCOUNTS (501-599)
            ['code' => 500, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'General Expenses', 'type' => null],
            ['code' => 501, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Cost of Goods Sold', 'type' => null],
            ['code' => 502, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Raw Materials', 'type' => null],
            ['code' => 503, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Direct Labor', 'type' => null],
            ['code' => 504, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Manufacturing Supplies', 'type' => null],
            ['code' => 505, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Rent Expense', 'type' => null],
            ['code' => 506, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Utilities Expense', 'type' => null],
            ['code' => 507, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Office Supplies', 'type' => null],
            ['code' => 520, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Salaries & Wages', 'type' => null],
            ['code' => 521, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Payroll Taxes', 'type' => null],
            ['code' => 530, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Travel Expenses', 'type' => null],
            ['code' => 531, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Meals & Entertainment', 'type' => null],
            ['code' => 540, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Bank Fees & Charges', 'type' => null],
        ]);
    }
}
