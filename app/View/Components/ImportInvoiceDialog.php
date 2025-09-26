<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Closure;

class ImportInvoiceDialog extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $clients
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.import-invoice-dialog');
    }
}
