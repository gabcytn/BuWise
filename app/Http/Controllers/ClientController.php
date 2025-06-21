<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Models\OrganizationMember;
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
    private static int $itemsPerPage = 5;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAnyClient', User::class);

        $user = $request->user();
        $roleId = $user->role_id;

        if ($roleId === Role::ACCOUNTANT)
            $clients = $user->clients();
        else
            $clients = $user->accountant->clients();

        $search = $request->query('search');
        $filter = $request->query('filter');

        if ($search != null)
            $clients = $clients
                ->where('name', 'like', "$search%")
                ->paginate(ClientController::$itemsPerPage)
                ->appends([
                    'search' => $search
                ]);
        else if ($filter != null) {
            switch ($filter) {
                case 'name':
                    $clients = $clients
                        ->orderBy('name')
                        ->paginate(ClientController::$itemsPerPage)
                        ->appends([
                            'filter' => 'name'
                        ]);
                    break;
                case 'date':
                    $clients = $clients
                        ->orderByRaw('created_at DESC')
                        ->paginate(ClientController::$itemsPerPage)
                        ->appends([
                            'filter' => 'date'
                        ]);
                    break;
                default:
                    break;
            }
        } else
            $clients = $clients->paginate(ClientController::$itemsPerPage);

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

        $filename = $this->storeImageToLocal($request, $validated['name']);

        $currentUser = $request->user();
        if ($currentUser->role_id === Role::ACCOUNTANT)
            $accountantId = $currentUser->id;
        else
            $accountantId = $currentUser->accountant->id;

        $client = User::create([
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

        $organization = $request->user()->organization;
        OrganizationMember::create([
            'user_id' => $client->id,
            'organization_id' => $organization->id,
        ]);
        UserCreated::dispatch($request->user(), $client);

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
        // Storage::delete('profile-images/' . $user->profile_img);
    }

    private function storeImageToAws(Request $request)
    {
        $path = $request->file('profile_img')->store('profile-images', 's3');
        return basename($path);
    }

    private function storeImageToLocal(Request $request, $name): string
    {
        $file = $request->file('profile_img');
        $filename = $name . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put("profiles/{$filename}", file_get_contents($file));

        return $filename;
    }
}
