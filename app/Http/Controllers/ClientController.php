<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAnyClient', User::class);

        $user = $request->user();
        $roleId = $user->role_id;

        $search = $request->query('search');

        if ($roleId === Role::ACCOUNTANT)
            $clients = $user->clients();
        else
            $clients = $user->accountant->clients();

        if ($search != null)
            $clients = $clients->where('name', 'like', "%$search%");

        $clients = $clients->paginate(2);
        return view('client.index', ['clients' => $clients]);
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

        // Upload to AWS:
        // $path = $request->file("profile_img")->store("images", "s3");

        $file = $request->file('profile_img');
        $filename = $validated['name'] . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put("profiles/{$filename}", file_get_contents($file));

        $currentUser = $request->user();
        if ($currentUser->role_id === Role::ACCOUNTANT)
            $accountantId = $currentUser->id;
        else
            $accountantId = $currentUser->accountant->id;

        User::create([
            'accountant_id' => $accountantId,
            'role_id' => Role::CLIENT,
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'tin' => $validated['tin'],
            'client_type' => $validated['client_type'],
            'name' => $validated['name'],
            'profile_img' => $filename,
            'password' => Hash::make($validated['password']),
        ]);

        // return Storage::disk("s3")->response("images/" . basename($path));
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
            $this->deleteOldImage($client);
            $filename = $validated['name'] . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put("profiles/{$filename}", file_get_contents($file));
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

        $this->deleteOldImage($client);
        User::destroy($client->id);

        return to_route('clients.index');
    }

    private function deleteOldImage(User $user): void
    {
        $path = $user->profile_img;
        Storage::disk('public')->delete('profiles/' . $path);
    }
}
