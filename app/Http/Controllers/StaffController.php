<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    private static int $itemsPerPage = 2;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAnyStaff', User::class);

        $user = $request->user();
        $staff = $user->staff();

        $search = $request->query('search');
        $filter = $request->query('filter');

        if ($search != null)
            $staff = $staff
                ->where('name', 'like', "$search%")
                ->paginate(StaffController::$itemsPerPage)
                ->appends([
                    'search' => $search
                ]);
        else if ($filter != null) {
            switch ($filter) {
                case 'name':
                    $staff = $staff
                        ->orderBy('name')
                        ->paginate(StaffController::$itemsPerPage)
                        ->appends([
                            'filter' => 'name'
                        ]);
                    break;
                case 'date':
                    $staff = $staff
                        ->orderByRaw('created_at DESC')
                        ->paginate(StaffController::$itemsPerPage)
                        ->appends([
                            'filter' => 'date'
                        ]);
                    break;
                default:
                    break;
            }
        } else
            $staff = $staff->paginate(2);

        return view('staff.index', ['staffs' => $staff]);
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
            'staff_type' => 'required',
            'password' => ['required', Password::min(8)],
            'profile_img' => ['required', File::image()->max(5000)]
        ]);

        $name = $validated['first_name'] . ' ' . $validated['last_name'];

        $file = $request->file('profile_img');
        $filename = $name . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put("profiles/{$filename}", file_get_contents($file));

        $staff = User::create([
            'name' => $name,
            'email' => $validated['email'],
            'accountant_id' => $request->user()->id,
            'role_id' => (int) $validated['staff_type'],
            'password' => Hash::make($validated['password']),
            'profile_img' => $filename,
        ]);
        UserCreated::dispatch($staff);

        // $request->user()->staff()->attach($staff->id);

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
            'staff_type' => 'required',
            'password' => [Password::min(8)],
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
            $this->deleteOldImage($staff);
            $filename = $name . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put("profiles/{$filename}", file_get_contents($file));
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

        $this->deleteOldImage($staff);
        User::destroy($staff->id);

        return to_route('staff.index');
    }

    private function deleteOldImage(User $user): void
    {
        $path = $user->profile_img;
        Storage::disk('public')->delete('profiles/' . $path);
    }
}
