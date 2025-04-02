<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check() && Auth::user()->status === 'Inactivo') {
      // Notificación en Filament
      Notification::make()
        ->title('Tu cuenta está desactivada.')
        ->icon('heroicon-o-shield-exclamation')
        ->color('danger')
        ->iconColor('danger')
        ->send();

      // Cerrar la sesión
      Auth::logout();

      // Redirigir a la página de inicio de sesión
      return redirect()->route('filament.app.auth.login')->withErrors([
        'status' => 'Tu cuenta está desactivada.',
      ]);
    }

    return $next($request);
  }
}
