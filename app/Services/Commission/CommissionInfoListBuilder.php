<?php

namespace App\Services\Commission;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class CommissionInfoListBuilder
{
  /**
   * Configure the commission infolist.
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
            'lg' => 3,
          ])
          ->schema([
            TextEntry::make('customer.name')
              ->label('Cliente')
              ->icon('heroicon-o-user'),
            TextEntry::make('status')
              ->label('Estado')
              ->badge()
              ->color(fn(string $state): string => match ($state) {
                'Pagada' => 'success',
                'Pendiente' => 'warning',
                default => 'gray',
              }),
            TextEntry::make('type')
              ->label('Tipo')
              ->badge()
              ->color(fn(string $state): string => match ($state) {
                'Plan' => 'plan',
                'Repocisión' => 'repo',
                'Kit financiado' => 'kit',
                default => 'gray',
              }),
            TextEntry::make('cellphone')
              ->label('Celular')
              ->default('-')
              ->icon('heroicon-o-device-phone-mobile'),
            TextEntry::make('date')
              ->label('Fecha')
              ->date()
              ->icon('heroicon-o-calendar-days'),
            TextEntry::make('price')
              ->label('Precio')
              ->formatStateUsing(function ($state) {
                return '$ ' . number_format($state, 0, ',', '.');
              })
              ->icon('heroicon-o-banknotes'),
            TextEntry::make('smartphone')
              ->label('Equipo')
              ->default('-')
              ->icon('heroicon-o-device-phone-mobile'),
            TextEntry::make('imei')
              ->label('IMEI')
              ->default('-')
              ->icon('heroicon-o-hashtag'),
            TextEntry::make('iccid')
              ->label('ICCID')
              ->default('-')
              ->icon('heroicon-o-hashtag'),
            TextEntry::make('user.name')
              ->label('Usuario')
              ->icon('heroicon-o-user'),
            TextEntry::make('notes')
              ->label('Notas')
              ->default('-')
              ->icon('heroicon-o-book-open'),
            // ->columnSpanFull(),
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
