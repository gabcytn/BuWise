<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class header extends Component
{
    public string $username;
    public string $role;
    /**
     * Create a new component instance.
     */
    public function __construct(string $username, string $role)
    {
        $this->username = $username;
        $this->role = $role;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
