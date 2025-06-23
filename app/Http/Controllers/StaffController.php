<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserControllerHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new UserControllerHelper('staff');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAnyStaff', User::class);
        return $this->helper->index($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('createStaff', User::class);
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|lowercase|max:255|email|unique:' . User::class,
            'staff_type' => 'required|in:2,3',
            'password' => ['required', Password::min(8)],
            'profile_img' => ['required', File::image()->max(5000)]
        ]);

        $name = $validated['first_name'] . ' ' . $validated['last_name'];
        $filename = $this->helper->storeImageToPublic($name, $request->file('profile_img'));

        $current_user = $request->user()->id;
        User::create([
            'name' => $name,
            'email' => $validated['email'],
            'accountant_id' => $current_user,
            'created_by' => $current_user,
            'role_id' => (int) $validated['staff_type'],
            'password' => Hash::make($validated['password']),
            'profile_img' => $filename,
        ]);

        return to_route('staff.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff)
    {
        Gate::authorize('updateStaff', $staff);
        return view('staff.edit', ['staff' => $staff]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $staff): RedirectResponse
    {
        Gate::authorize('updateStaff', $staff);
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'string', 'lowercase', 'max:255', 'email', Rule::unique('users')->ignore($staff->id)],
            'staff_type' => 'required|in:2,3',
            'password' => ['nullable', Password::min(8)],
            'profile_img' => [File::image()->max(5000)]
        ]);

        $name = $validated['first_name'] . ' ' . $validated['last_name'];

        $data = [
            'name' => $name,
            'email' => $validated['email'],
            'role_id' => (int) $validated['staff_type'],
            'password' => Hash::make($validated['password'])
        ];

        $file = $request->file('profile_img');
        if ($file !== null) {
            $this->helper->deleteProfilePicture($staff);
            $filename = $this->helper->storeImageToPublic($name, $file);
            $data['profile_img'] = $filename;
        }

        $staff->update($data);
        return to_route('staff.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff): RedirectResponse
    {
        Gate::authorize('deleteStaff', $staff);

        $this->helper->deleteProfilePicture($staff);
        User::destroy($staff->id);

        return to_route('staff.index');
    }
}
