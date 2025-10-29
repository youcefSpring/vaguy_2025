<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wilaya;

class InfluencerWilaya extends Component
{
    public $wilayas,$influencer_wilaya;

    public function render()
    {
        $this->wilayas=Wilaya::get(['id','code','name']);
        return view('livewire.influencer-wilaya');
    }
}
