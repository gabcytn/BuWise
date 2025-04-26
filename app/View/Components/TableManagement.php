<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class TableManagement extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $headers,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-management');
    }
}
