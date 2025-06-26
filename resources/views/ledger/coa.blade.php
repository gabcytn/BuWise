@php
    $headers = ['ACCOUNT CODE', 'ACCOUNT NAME', 'ACCOUNT TYPE', 'ACTION'];
@endphp
@vite(['resources/js/ledger/coa.js', 'resources/css/ledger/coa.css'])

<x-app-layout>
    <div class="coa-page-header">
        <div class="left">
            <h2>All Accounts</h2>
            <p>Manage your organization’s Chart of Accounts</p>
        </div>
        <div class="right" style="display: flex; align-items: center; gap: 0.5rem;">
            <button class="add-account-btn">+ Add Account</button>
            <button class="more-btn" aria-label="More options">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#1f2d3d">
                    <circle cx="12" cy="5" r="2" />
                    <circle cx="12" cy="12" r="2" />
                    <circle cx="12" cy="19" r="2" />
                </svg>
            </button>
        </div>
    </div>
    <div class="coa-box">
        <form class="coa-controls" id="ledger-form" action="#">
            <div class="coa-filters">
                <div class="type-select-wrapper">
                    <img src="{{ asset('images/filterbyicon.png') }}" alt="Filter Icon"
                        class="btn-icon left-icon client-icon">
                    <select class="type-select">
                        <option value="" selected disabled>Filter by: Type</option>
                        <option value="all">All</option>
                        @foreach (\App\Models\AccountGroup::all() as $item)
                            <option value="{{ strtolower($item->name) }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <img src="{{ asset('images/menudown.png') }}" alt="Menu Down" class="btn-icon menu-down-icon">
                </div>
                <div class="client-select-wrapper">
                    <img src="{{ asset('images/allclientsicon.png') }}" alt="Client Icon"
                        class="btn-icon left-icon client-icon">
                    <select class="client-select">
                        <option value="" selected disabled>All Clients</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <img src="{{ asset('images/menudown.png') }}" alt="Menu Down"
                        class="btn-icon right-icon dropdown-icon">
                </div>
            </div>
            <div class="search-wrapper">
                <img src="{{ asset('images/magnify.png') }}" alt="Search Icon" class="search-icon">
                <input type="search" id="account-search" placeholder="Search Accounts.." />
            </div>
        </form>
        <div class="coa-table-wrapper">
            <table class="coa-table">
                <thead>
                    <tr>
                        @foreach ($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr class="ledger-account" data-account-code="{{ $account->id }}">
                            <td>{{ $account->code }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ ucfirst($account->accountGroup->name) }}</td>
                            <td class="action-column">
                                @if ($account->accountant_id)
                                    <form action="{{ route('ledger.delete-account', $account) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submi"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @else
                                    <button type="button"><i class="fa-solid fa-lock"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <dialog id="add-new-account">
        <h2>Add New Account</h2>
        <form action="{{ route('ledger.create-account') }}" method="POST">
            @csrf
            <div class="dialog-padding">
                <div class="dialog-top">
                    <div class="dialog-input-wrapper">
                        <label for="account-type">Account Type</label>
                        <select name="account_type" id="account-type" required>
                            <option value="" selected disabled>Select a Type</option>
                            @foreach ($accountGroups as $accountGroup)
                                <option value="{{ $accountGroup->id }}">{{ $accountGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="dialog-input-wrapper">
                        <label for="account-name">Account Name</label>
                        <input name="account_name" id="account-name" required />
                    </div>
                    <div class="dialog-input-wrapper">
                        <label for="account-code">Account Code</label>
                        <input type="number" name="account_code" id="account-code" required />
                    </div>
                </div>
                <div class="dialog-description">
                    <label for="account-description">Description</label>
                    <textarea rows="5" id="account-description" name="account_description"></textarea>
                </div>
            </div>
            <hr />
            <div class="dialog-padding dialog-button-container">
                <button type="submit">Save</button>
                <button type="button" id="close-account-dialog-btn">Cancel</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
