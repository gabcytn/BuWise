<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Client::class);

        $user = $request->user();
        $roleName = $user->role->name;

        if ($roleName === "accountant")
            $clients = $user->clients;
        else
            $clients = $user->accountant->clients;

        return view("client.index", ["clients" => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Client::class);
        return view("client.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Client::class);
        $validated = $request->validate([
            "email" => "required|string|lowercase|max:255|email|unique:" . Client::class,
            "name" => "required|string|max:255",
            "phone_number" => "required|string|regex:/^0\d{10}$/",
            "tin" => "required|numeric",
            "client_type" => "required|string|max:100",
            "profile_img" => ["required", File::image()->max(5000)],
        ]);

        // Upload to AWS:
        // $path = $request->file("profile_img")->store("images", "s3");

        $file = $request->file("profile_img");
        $filename = $validated["name"] . '_' . uniqid() . "." . $file->getClientOriginalExtension();
        Storage::disk("public")->put("profiles/{$filename}", file_get_contents($file));

        $currentUser = $request->user();
        if ($currentUser->role->name === "accountant")
            $accountantId = $currentUser->id;
        else
            $accountantId = $currentUser->accountant->id;

        Client::create([
            "accountant_id" => $accountantId,
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "tin" => $validated["tin"],
            "client_type" => $validated["client_type"],
            "name" => $validated["name"],
            "profile_img" => $filename,
            "password" => Hash::make(env("CLIENT_DEFAULT_PASSWORD")),
        ]);

        // return Storage::disk("s3")->response("images/" . basename($path));
        return to_route("clients.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        Gate::authorize("update", $client);
        return view("client.edit", ["client" => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        Gate::authorize("update", $client);

        $validated = $request->validate([
            "email" => "string|lowercase|max:255|email",
            "name" => "string|max:255",
            "phone_number" => "string|regex:/^0\d{10}$/",
            "tin" => "numeric",
            "client_type" => "string|max:100",
            "profile_img" => [File::image()->max(5000)],
        ]);

        $data = [
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "tin" => $validated["tin"],
            "client_type" => $validated["client_type"],
            "name" => $validated["name"],
        ];

        $file = $request->file("profile_img");
        if ($file !== null) {
            $filename = $validated["name"] . '_' . uniqid() . "." . $file->getClientOriginalExtension();
            Storage::disk("public")->put("profiles/{$filename}", file_get_contents($file));
            $data["profile_img"] = $filename;
        }

        // TODO: delete old profile image
        $client->update($data);
        return to_route("clients.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        Gate::authorize("delete", $client);
        Client::destroy($client->id);

        // TODO: delete profile image in the storage
        return to_route("clients.index");
    }
}
