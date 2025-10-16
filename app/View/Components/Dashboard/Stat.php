<?php
namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class Stat extends Component
{

    
    public ?string $icon;
    public ?string $description;
    public ?string $color;

    public function __construct(
  
       
        ?string $icon = null,
        ?string $description = null,
        ?string $color = 'primary'
    ) {
       
       
        $this->icon = $icon;
        $this->description = $description;
        $this->color = $color;
    }

    public function render()
    {
        return view('components.dashboard.stat');
    }
}
