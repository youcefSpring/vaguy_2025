<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Select extends Component
{
    public $campain_social_media_multiple=[];
    public function mount(){
        $this->campain_social_media_multiple[0]="facebook";
    }
    public function updatedCampainSocialMedia(){
        $this->emit('postAdded');
    }
    public function render()
    {
        return view('livewire.select');
    }
}
