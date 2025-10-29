<?php
namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Wilaya;
use App\Models\Category;
class Select2Dropdown extends Component
{
    public $ottPlatform = '',$wilayas;
    public $name_select='';

    public $webseries = [
        'Wanda Vision',
        'Money Heist',
        'Lucifer',
        'Stranger Things'
    ];
    public function render()
    {
        $this->allCategory = Category::active()->orderBy('name')->get();
          $this->wilayas=Wilaya::get(['id','code','name']);
        return view('livewire.select2-dropdown');
    }
}
