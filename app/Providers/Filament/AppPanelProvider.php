<?php

namespace App\Providers\Filament;

use App\Http\Middleware\CheckUserStatus;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AppPanelProvider extends PanelProvider
{
  public function panel(Panel $panel): Panel
  {
    return $panel
      ->default()
      ->id('app')
      ->path('app')
      ->login()
      ->colors([
        'primary' => Color::Red,
        'kit' => Color::Lime,
        'plan' => Color::Blue,
        'repo' => Color::Cyan,
      ])
      ->font('Sora')
      ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
      ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
      ->pages([
        Pages\Dashboard::class,
      ])
      ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
      ->widgets([
        Widgets\AccountWidget::class,
        // Widgets\FilamentInfoWidget::class,
      ])
      ->plugins([
        FilamentEditProfilePlugin::make()
          ->slug('perfil')
          ->setTitle('Perfil')
          ->setIcon('heroicon-o-identification')
          ->setNavigationLabel('Perfil')
          ->shouldShowDeleteAccountForm(false)
          ->shouldRegisterNavigation(false),
      ])
      ->userMenuItems([
        'profile' => MenuItem::make()
          ->label(fn() => Auth::user()->name)
          ->url(fn(): string => EditProfilePage::getUrl())
          ->icon('heroicon-o-user-circle')
      ])
      ->middleware([
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        AuthenticateSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        SubstituteBindings::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
        CheckUserStatus::class,
      ])
      ->authMiddleware([
        Authenticate::class,
      ]);
  }
}
