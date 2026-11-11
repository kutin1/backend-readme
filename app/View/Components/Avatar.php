<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public $src;

    public function __construct($src = null)
    {
        $this->src = $src;
    }

    public function render()
    {
        return view('components.avatar');
    }
}
