<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->user()->onboarded)
            return to_route('dashboard');
        return view('profile.onboarding');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'logo' => ['required', File::image()->max(10000)]
        ]);

        $filename = time() . '_' . \Illuminate\Support\Str::uuid();
        $request->file('logo')->storeAs('organizations/', $filename, 'public');

        $user = $request->user();
        $user->onboarded = true;
        $user->save();

        $org = Organization::create([
            'name' => $request->name,
            'address' => $request->address,
            'logo' => $filename,
        ]);
        OrganizationMember::create([
            'user_id' => $user->id,
            'organization_id' => $org->id,
        ]);

        return to_route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
