<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Invoice</title>
    @vite('resources/css/invoices/add.css')
</head>
<x-app-layout>
    <div class="invoice-container">
        <h2 class="page-title">Add Invoice</h2>
        <div class="invoice-form">

            <div class="form-section left">
                <div class="image-placeholder">
                    <span>📎 Image Here</span>
                </div>
            </div>

            <div class="form-section right">
                <div class="form-row">
                    <label>Issue Date</label>
                    <input type="date" />
                </div>
                <div class="form-row">
                    <label>Due Date</label>
                    <input type="date" />
                </div>
                <div class="form-row">
                    <label>Transaction Type</label>
                    <select>
                        <option>Select Transaction Type</option>
                    </select>
                </div>
                <div class="form-row">
                    <label>Invoice Number</label>
                    <input type="text" placeholder="Enter Invoice Number" />
                </div>
                <div class="form-row">
                    <label>Name of Supplier</label>
                    <input type="text" placeholder="Enter Name of Supplier" />
                </div>
                <div class="form-row">
                    <label>Invoice Status</label>
                    <label><input type="checkbox" checked> Paid</label>
                    <label><input type="checkbox"> Unpaid</label>
                </div>
                <div class="form-row">
                    <label>Payment Method</label>
                    <select><option>Select Payment Type</option></select>
                </div>
                <div class="form-row">
                    <label>Tax Type</label>
                    <select><option>Select Tax Type</option></select>
                    <small>Only select this option if tax applies as a whole.</small>
                </div>
                <div class="form-row">
                    <label>Discount Type</label>
                    <select><option>Select Discount Type</option></select>
                    <small>Only select this option if a discount applies as a whole.</small>
                </div>
            </div>
        </div>

        <table class="invoice-items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Net Amount</th>
                    <th>Less: Discount</th>
                    <th>Tax</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sample Item</td>
                    <td><input type="number" value="0" /></td>
                    <td><input type="number" value="0.00" step="0.01" /></td>
                    <td><input type="number" value="0.00" step="0.01" /></td>
                    <td>
                        <select><option>Select Discount</option></select>
                    </td>
                    <td>
                        <select><option>Select Tax Type</option></select>
                    </td>
                    <td><input type="number" value="0.00" step="0.01" /></td>
                </tr>
            </tbody>
        </table>

        <div class="actions">
            <button class="btn add-row">+ Add New Row</button>
            <div class="submit-buttons">
                <button class="btn btn-primary">Proceed to Journal</button>
                <button class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
</x-app-layout>
