<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): view 
    {
        return view('auth_login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credenciais = $request->only('email', 'password');

        if (! Auth::attempting($credenciais, $request->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'email' => 'E-mail ou senha incorretas.',
                ]);
        }

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
