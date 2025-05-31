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
            ['code' => 100, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash'],
            ['code' => 101, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash in Bank (Checking Account)'],
            ['code' => 102, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash in Bank (Savings Account)'],
            ['code' => 103, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Petty Cash'],
            ['code' => 104, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Accounts Receivable'],
            ['code' => 105, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Input VAT Receivable'],
            ['code' => 106, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Inventory'],
            ['code' => 107, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Prepaid Expenses'],
            // Assets - Fixed Assets
            ['code' => 150, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Land'],
            ['code' => 151, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Buildings'],
            ['code' => 152, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Vehicles'],
            ['code' => 153, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Equipment'],
            ['code' => 154, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Furniture and Fixtures'],
            // Assets - Other Assets
            ['code' => 170, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Investments'],
            ['code' => 171, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Goodwill'],
            ['code' => 172, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Intangible Assets'],
            // Liabilities - Current
            ['code' => 200, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Accounts Payable'],
            ['code' => 201, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Credit Card Payable'],
            ['code' => 202, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Wages Payable'],
            ['code' => 203, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Taxes Payable'],
            ['code' => 204, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Output VAT Payable'],
            ['code' => 205, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Short-Term Loans'],
            ['code' => 206, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Customer Deposits'],
            // Liabilities - Long Term
            ['code' => 250, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Mortgage Payable'],
            ['code' => 251, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Long-Term Loans'],
            ['code' => 252, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Bonds Payable'],
            // Equity
            ['code' => 300, 'account_group_id' => AccountGroup::EQUITY, 'name' => "Owner's Capital"],
            ['code' => 301, 'account_group_id' => AccountGroup::EQUITY, 'name' => "Owner's Drawings"],
            ['code' => 310, 'account_group_id' => AccountGroup::EQUITY, 'name' => 'Retained Earnings'],
            // REVENUE ACCOUNTS (401-499)
            ['code' => 400, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales'],
            ['code' => 401, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales Returns and Allowances'],
            ['code' => 402, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales Discounts'],
            ['code' => 403, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Commissions Earned'],
            ['code' => 405, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Rent Revenue'],
            ['code' => 410, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Other Income'],
            // EXPENSE ACCOUNTS (501-599)
            ['code' => 500, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'General Expenses'],
            ['code' => 501, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Cost of Goods Sold'],
            ['code' => 502, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Raw Materials'],
            ['code' => 503, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Direct Labor'],
            ['code' => 504, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Manufacturing Supplies'],
            ['code' => 505, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Rent Expense'],
            ['code' => 506, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Utilities Expense'],
            ['code' => 507, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Office Supplies'],
            ['code' => 520, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Salaries & Wages'],
            ['code' => 521, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Payroll Taxes'],
            ['code' => 530, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Travel Expenses'],
            ['code' => 531, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Meals & Entertainment'],
            ['code' => 540, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Bank Fees & Charges'],
        ]);
    }
}
