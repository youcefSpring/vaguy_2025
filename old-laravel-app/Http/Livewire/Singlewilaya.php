<?php

namespace App\Http\Livewire;
use App\Models\Wilaya;
use Livewire\Component;

class Singlewilaya extends Component
{
    public $wilayas;
    public function render()
    {
        $this->wilayas=Wilaya::get(['id','code','name']);
        return view('livewire.singlewilaya');
    }
}
