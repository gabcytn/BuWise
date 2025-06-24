<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserControllerHelper
{
    private const ITEMS_PER_PAGE = 5;

    private string $route;

    public function __construct(string $route)
    {
        if (in_array($route, ['client', 'staff']))
            $this->route = $route;
        else
            throw new \Exception('Route must be either client or staff');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $users = $this->getUsers($user);
        $filters = [
            'search' => $request->input('search', null),
            'filter' => $request->input('filter', null),
            'period' => $request->input('period', null),
        ];

        if ($filters['search'])
            $users = $users->whereLike('name', '%' . $filters['search'] . '%');
        if ($filters['filter']) {
            switch ($filters['filter']) {
                case 'name':
                    $users = $users->orderBy('name');
                    break;
                case 'date':
                    $users = $users->orderByDesc('created_at');
                    break;
                default:
                    break;
            }
        }
        if ($filters['period']) {
            $period = getStartAndEndDate($filters['period']);
            $users = $users->whereBetween('created_at', [$period[0], $period[1]]);
        }

        $users = $users->paginate(self::ITEMS_PER_PAGE)->appends($filters);

        return view($this->route . '.index', ['users' => $users]);
    }

    private function getUsers(User $user)
    {
        $route = $this->route;
        switch ($route) {
            case 'client':
                $roleId = $user->role_id;
                if ($roleId === Role::ACCOUNTANT)
                    return $user->clients();
                else
                    return $user->accountant->clients();
                break;
            case 'staff':
                return $user->staff();
                break;
            default:
                break;
        }
    }

    public function deleteProfilePicture(User $user)
    {
        $path = $user->profile_img;
        Storage::disk('public')->delete('profiles/' . $path);
    }

    public function storeImageToPublic(string $user_name, UploadedFile $file): string
    {
        $filename = Str::snake(Str::lower($user_name)) . '_' . time() . '_' . Str::uuid();
        $filename .= '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put("profiles/{$filename}", file_get_contents($file));

        return $filename;
    }
}
