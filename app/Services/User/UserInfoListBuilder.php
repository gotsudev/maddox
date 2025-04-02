<?php

namespace App\Services\User;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class UserInfoListBuilder
{
  /**
   * Configure the user infolist.
   *
   * @param Infolist $infolist
   * @return Infolist
   */
  public static function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Section::make('')
          ->columns([
            'default' => 1,
            'md' => 3,
          ])
          ->schema([
            TextEntry::make('name')
              ->label('Nombre')
              ->icon('heroicon-o-user'),
            TextEntry::make('email')
              ->label('Correo electrónico')
              ->icon('heroicon-o-envelope'),
            TextEntry::make('cellphone')
              ->label('Celular')
              ->icon('heroicon-o-device-phone-mobile'),
            /*  TextEntry::make('roles.name')
              ->label('Rol:')
              ->badge()
              ->color('primary')
              ->icon('heroicon-o-device-shield-check'), */ // !TODO: Descomentar cuando se tenga la relación
            TextEntry::make('status')
              ->label('Estado')
              ->badge()
              ->color(fn(string $state): string => match ($state) {
                'Activo' => 'success',
                'Inactivo' => 'warning',
              }),
            TextEntry::make('created_at')
              ->label('Creado')
              ->dateTime()
              ->icon('heroicon-o-calendar-days'),
            TextEntry::make('updated_at')
              ->label('Actualizado')
              ->dateTime()
              ->icon('heroicon-o-calendar-days'),
          ]),
      ]);
  }
}
