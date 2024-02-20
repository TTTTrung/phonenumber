<?php

namespace App\Livewire;

use App\Models\Phonenum;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class Phoneadmin extends Component
{
    use WithPagination;
    public $searchPhone = "";
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $editId;
    public $phonenumber;
    public $name;
    public $department;
    public $building;
    public $role;

    public $ephonenumber;
    public $enamephone;
    public $edepartment;
    public $ebuilding;
    public $erole;

    public $deleteId;

    public $selectedPhoneData;

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function hideCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['phonenumber', 'name', 'department', 'building', 'role']);
    }

    public function saveData()
    {
        $this->phonenumber = trim($this->phonenumber);
        $this->validate([
            'phonenumber' => 'required|numeric|digits_between:3,15',
            'name' => 'required|string',
            'department' => 'required|string',
            'building' => 'required|string',
            'role' => 'required|string',
        ]);

        Phonenum::create([
            'phonenumber' => $this->phonenumber,
            'namephone' => $this->name,
            'department' => $this->department,
            'building' => $this->building,
            'role' => $this->role,
            'user_id' => auth()->id(),
        ]);

        $this->reset(['phonenumber', 'name', 'department', 'building', 'role']);
        $this->showCreateModal = false;
    }
    public function openEditModal($id)
    {
        $this->editId = $id;
        $this->selectedPhoneData = Phonenum::find($id);
        $this->showEditModal = true;
    }

    public function hideEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['ephonenumber', 'enamephone', 'edepartment', 'ebuilding', 'erole','selectedPhoneData']);
    }

    public function editData()
    {
        // Validation for each field
        $this->ephonenumber = trim($this->ephonenumber);
    $validatedData = $this->validate([
        'ephonenumber' => $this->ephonenumber !== null ? 'numeric|digits_between:3,15' : '',
        'enamephone' => $this->enamephone !== null ? 'string' : '',
        'edepartment' => $this->edepartment !== null ? 'string' : '',
        'ebuilding' => $this->ebuilding !== null ? 'string' : '',
        'erole' => $this->erole !== null ? 'string' : '',
    ]);

    // Update using validated data or existing data
    Phonenum::where('id', $this->selectedPhoneData['id'])->update([
        'phonenumber' => $this->ephonenumber !== null ? $validatedData['ephonenumber'] : $this->selectedPhoneData['phonenumber'],
        'namephone' => $this->enamephone !== null ? $validatedData['enamephone'] : $this->selectedPhoneData['namephone'],
        'department' => $this->edepartment !== null ? $validatedData['edepartment'] : $this->selectedPhoneData['department'],
        'building' => $this->ebuilding !== null ? $validatedData['ebuilding'] : $this->selectedPhoneData['building'],
        'role' => $this->erole !== null ? $validatedData['erole'] : $this->selectedPhoneData['role'],
    ]);
         
        $this->hideEditModal();
    }

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->selectedPhoneData = Phonenum::find($id);
        $this->showDeleteModal = true;
    }

    public function hideDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->reset('selectedPhoneData');
        
    }

    public function deleteData()
    {
        Phonenum::where('id', $this -> selectedPhoneData['id'])->delete();
        $this->hideDeleteModal();
    }

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


            // $phonedatas = Phonenum::all();
        return view('livewire.phoneadmin',compact('phonedatas'));
    }
}
