<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FilterModal extends Component
{
    public $show=1;
    public function render()
    {
        return view('livewire.filter-modal');
    }
}
