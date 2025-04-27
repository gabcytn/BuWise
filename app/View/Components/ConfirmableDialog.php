<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class ConfirmableDialog extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.confirmable-dialog');
    }
}
