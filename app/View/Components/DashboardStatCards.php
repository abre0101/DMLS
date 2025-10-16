<?php


namespace App\View\Components;

use Illuminate\View\Component;

class DashboardStatCards extends Component
{
    public $stats;

    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    public function render()
    {
        return view('components.dashboard-stat-cards');
    }
}
