<?php

namespace App\View\Components\Professor;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddExam extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.professor.add-exam');
    }
}
