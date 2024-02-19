<?php

namespace App\Livewire;

use App\Models\Phonenum;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Phoneque extends Component
{
    
    public string $searchPhone = "";

    public function render()
    {
        $phonedatas = Phonenum::select('id','namephone','phonenumber','department','role','building')
            ->when($this->searchPhone !== '', function (Builder $query){
                $query->where(function (Builder $subquery){
                    $subquery->where('namephone', 'like', '%'. $this->searchPhone . '%')
                        ->orWhere('phonenumber', 'like', '%' . $this->searchPhone . '%')
                        ->orWhere('department', 'like', '%'. $this->searchPhone . '%');
                });
            })->orderby('department','asc')->paginate(10);
        return view('livewire.phoneque',compact('phonedatas'));
    }
}
