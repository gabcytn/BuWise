<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class ProfileInformationController extends Controller
{
    /*
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'lowercase', 'max:255', 'email', Rule::unique('users')->ignore($user->id)],
            'gender' => ['required', 'in:m,f,n'],
            'profile_img' => ['nullable', File::image()->max(5000)],
        ]);

        if ($user->role_id === Role::ACCOUNTANT) {
            $request->validate([
                'organization_name' => ['required', 'string'],
                'organization_address' => ['required', 'string'],
            ]);
            $organization = $user->organization;
            $organization->name = $request->organization_name;
            $organization->address = $request->organization_address;
            $organization->save();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
        ];

        if ($request->profile_img) {
            $file = $request->file('profile_img');
            $filename = basename($file);
            $path = "profiles/{$filename}";
            Storage::disk('public')->put($path, file_get_contents($file));
            $data['profile_img'] = $filename;

            Storage::disk('public')->delete('profiles/' . $user->profile_img);
        }

        $user->update($data);
        return back();
    }
}
