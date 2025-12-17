<?php

namespace App\View\Components;


use Illuminate\View\Component;

class SectionTitle extends Component
{
    public string $icon;
    public string $title;

    public function __construct(string $icon, string $title)
    {
        $this->icon = $icon;
        $this->title = $title;
    }

    public function render()
    {
        return view('components.section-title');
    }
}
