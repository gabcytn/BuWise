<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Closure;

class DashboardTodoList extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $tasks,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-todo-list');
    }
}
