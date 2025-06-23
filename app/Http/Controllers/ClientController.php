<?php

namespace App\Http\Controllers;

use App\Events\ClientDeleted;
use App\Models\Role;
use App\Models\User;
use App\Services\UserControllerHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new UserControllerHelper('client');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAnyClient', User::class);
        return $this->helper->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('createClient', User::class);
        $validated = $request->validate([
            'email' => 'required|string|lowercase|max:255|email|unique:' . User::class,
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^0\d{10}$/',
            'tin' => 'required|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'client_type' => 'required|string|max:100',
            'password' => ['required', Password::min(8)],
            'profile_img' => ['required', File::image()->max(5000)],
        ]);

        $filename =
            $this->helper->storeImageToPublic($request->name, $request->file('profile_img'));

        $currentUser = $request->user();
        $accountantId = getAccountantId($currentUser);

        User::create([
            'accountant_id' => $accountantId,
            'created_by' => $request->user()->id,
            'role_id' => Role::CLIENT,
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'tin' => $validated['tin'],
            'client_type' => $validated['client_type'],
            'name' => $validated['name'],
            'profile_img' => $filename,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $client)
    {
        Gate::authorize('updateClient', $client);

        return view('client.edit', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $client)
    {
        Gate::authorize('updateClient', $client);

        $validated = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'max:255', 'email', Rule::unique('users')->ignore($client->id)],
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^0\d{10}$/',
            'tin' => 'required|string|regex:/^\d{3}-\d{3}-\d{3}$/',
            'client_type' => 'required|string|max:100',
            'password' => ['nullable', Password::min(8)],
            'profile_img' => [File::image()->max(5000)],
        ]);

        $data = [
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'tin' => $validated['tin'],
            'password' => Hash::make($validated['password']),
            'client_type' => $validated['client_type'],
            'name' => $validated['name'],
        ];

        $file = $request->file('profile_img');
        if ($file !== null) {
            $this->helper->deleteProfilePicture($client);
            $filename = $this->helper->storeImageToPublic($request->name, $file);
            $data['profile_img'] = $filename;
        }

        $client->update($data);
        return to_route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $client): RedirectResponse
    {
        Gate::authorize('deleteClient', $client);

        $accountant_id = $client->accountant_id;

        $this->helper->deleteProfilePicture($client);
        User::destroy($client->id);

        // update clients cache
        ClientDeleted::dispatch($accountant_id);

        return to_route('clients.index');
    }
}
