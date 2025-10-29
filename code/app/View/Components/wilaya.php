<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Wilaya as W;

class wilaya extends Component
{
    public $wilayas;
    public function __construct()
    {
        //
        $this->wilayas=W::get(['id','code','name']);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.wilaya');
    }
}
