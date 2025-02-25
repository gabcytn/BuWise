<?php

namespace App\Http\Controllers;

use App\Models\Client;
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
        $clients = $user->clients;

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

        Client::create([
            "bookkeeper_id" => $request->user()->id,
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "tin" => $validated["tin"],
            "client_type" => $validated["client_type"],
            "name" => $validated["name"],
            "profile_img" => $filename,
            "password" => Hash::make(env("CLIENT_DEFAULT_PASSWORD")),
        ]);

        // return Storage::disk("s3")->response("images/" . basename($path));
        return to_route("client.create");
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // Only shows profile image of client
        // TODO: display appropriate view

        if (!Storage::disk("public")->exists("profiles/" . $client->profile_img)) {
            abort(404);
        }

        $url = asset("storage/profiles/" . $client->profile_img);

        return view("client.show", ["image" => $url]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
