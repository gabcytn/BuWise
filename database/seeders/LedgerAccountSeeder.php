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
            ['id' => 1000, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash on Hand'],
            ['id' => 1010, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash in Bank (Checking Account)'],
            ['id' => 1020, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Cash in Bank (Savings Account)'],
            ['id' => 1030, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Accounts Receivable'],
            ['id' => 1040, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Inventory'],
            ['id' => 1050, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Prepaid Expenses'],
            ['id' => 1060, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Petty Cash'],
            // Assets - Fixed Assets
            ['id' => 1500, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Land'],
            ['id' => 1510, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Buildings'],
            ['id' => 1520, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Vehicles'],
            ['id' => 1530, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Equipment'],
            ['id' => 1540, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Furniture and Fixtures'],
            // Assets - Other Assets
            ['id' => 1700, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Investments'],
            ['id' => 1710, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Goodwill'],
            ['id' => 1720, 'account_group_id' => AccountGroup::ASSETS, 'name' => 'Intangible Assets'],
            // Liabilities - Current
            ['id' => 2000, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Accounts Payable'],
            ['id' => 2010, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Credit Card Payable'],
            ['id' => 2020, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Wages Payable'],
            ['id' => 2030, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Taxes Payable'],
            ['id' => 2040, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Short-Term Loans'],
            ['id' => 2050, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Customer Deposits'],
            // Liabilities - Long Term
            ['id' => 2500, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Mortgage Payable'],
            ['id' => 2510, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Long-Term Loans'],
            ['id' => 2520, 'account_group_id' => AccountGroup::LIABILITIES, 'name' => 'Bonds Payable'],
            // Equity
            ['id' => 3000, 'account_group_id' => AccountGroup::EQUITY, 'name' => "Owner's Capital"],
            ['id' => 3010, 'account_group_id' => AccountGroup::EQUITY, 'name' => "Owner's Drawings"],
            ['id' => 3100, 'account_group_id' => AccountGroup::EQUITY, 'name' => 'Retained Earnings'],
            // Revenue
            ['id' => 4000, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Sales Revenue'],
            ['id' => 4010, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Service Revenue'],
            ['id' => 4020, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Interest Income'],
            ['id' => 4030, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Rental Income'],
            ['id' => 4040, 'account_group_id' => AccountGroup::REVENUE, 'name' => 'Other Income'],
            // Expenses - Cost of Goods Sold
            ['id' => 5000, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Cost of Goods Sold'],
            ['id' => 5010, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Raw Materials'],
            ['id' => 5020, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Direct Labor'],
            ['id' => 5030, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Manufacturing Supplies'],
            // Expenses - Operating
            ['id' => 6000, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Rent Expense'],
            ['id' => 6010, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Utilities Expense'],
            ['id' => 6020, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Internet & Phone Expense'],
            ['id' => 6030, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Office Supplies'],
            ['id' => 6040, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Insurance Expense'],
            ['id' => 6050, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Depreciation Expense'],
            ['id' => 6060, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Professional Fees'],
            ['id' => 6100, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Advertising Expense'],
            ['id' => 6110, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Social Media & Digital Marketing'],
            ['id' => 6120, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Promotional Materials'],
            ['id' => 6200, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Salaries & Wages'],
            ['id' => 6210, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Payroll Taxes'],
            ['id' => 6220, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Employee Benefits'],
            ['id' => 6230, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Training & Development'],
            ['id' => 6300, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Travel Expenses'],
            ['id' => 6310, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Meals & Entertainment'],
            ['id' => 6320, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Vehicle Expenses'],
            ['id' => 6400, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Bank Fees & Charges'],
            ['id' => 6410, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Interest Expense'],
            ['id' => 6420, 'account_group_id' => AccountGroup::EXPENSES, 'name' => 'Loan Repayments'],
        ]);
    }
}
