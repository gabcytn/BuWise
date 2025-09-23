<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class DashboardPieChartCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $typeCount,
        public array $clientTypes,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-pie-chart-card');
    }
}
