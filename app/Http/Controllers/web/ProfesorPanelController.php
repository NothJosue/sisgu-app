<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Profesor;

class ProfesorPanelController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $profesor = Profesor::where('usuario_id', $user->id)->firstOrFail();

        return view('profesor.dashboard', compact('profesor'));
    }
}
