<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalDonatur extends Component
{
    public $id;
    public $idForm;

    public function __construct($id, $idForm)
    {
        $this->id;
        $this->idForm;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-donatur');
    }
}
