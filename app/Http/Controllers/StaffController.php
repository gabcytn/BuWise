<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize("viewAny", User::class);

        $user = $request->user();
        $staff = $user->staff;

        return view("staff.index", ["staffs" => $staff]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize("create", User::class);
        return view("staff.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize("create", User::class);
        $validated = $request->validate([
            "first_name" => "required|string|max:100",
            "last_name" => "required|string|max:100",
            "email" => "required|string|lowercase|max:255|email|unique:" . User::class,
            "staff_type" => ["required", Rule::in(["liaison", "clerk"])],
            "password" => ["required", Password::min(8)],
            "profile_img" => ["required", File::image()->max(5000)]
        ]);

        $name = $validated["first_name"] . " " . $validated["last_name"];

        $file = $request->file("profile_img");
        $filename = $name . '_' . uniqid() . "." . $file->getClientOriginalExtension();
        Storage::disk("public")->put("profiles/{$filename}", file_get_contents($file));

        User::create([
            "name" => $name,
            "email" => $validated["email"],
            "accountant_id" => $request->user()->id,
            "role_id" => Role::where("name", $validated["staff_type"])->first()->id,
            "password" => Hash::make($validated["password"]),
            "profile_img" => $filename,
        ]);

        // $request->user()->staff()->attach($staff->id);

        return to_route("staff.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $staff)
    {
        Gate::authorize("update", $staff);
        return view("staff.edit", ["staff" => $staff]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: update a staff instance
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $staff)
    {
        Gate::authorize("delete", $staff);

        User::destroy($staff->id);
        return to_route("staff.index");
    }
}
