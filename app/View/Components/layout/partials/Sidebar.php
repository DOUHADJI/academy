<?php

namespace App\View\Components\layout\partials;

use App\Models\Admission;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    public function has_admissions():bool
    {
        return Admission::where('user_id', Auth::id())->exists();   
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.partials.sidebar');
    }
}