<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Slip Gaji - Sajadadir')]

class Salary extends Component
{
    public function render()
    {
        return view('livewire.salary');
    }
}
