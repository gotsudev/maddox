<?php

namespace App\Services\Commission;

use Exception;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class CommissionTableBuilder
{
  /**
   * Configure the supplier table.
   *
   * @param Table $table
   * @return Table
   */
  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')
          ->label('ID')
          ->searchable()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('customer.name')
          ->label('Cliente')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('status')
          ->label('Estado')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'Pagada' => 'success',
            'Pendiente' => 'warning',
            default => 'gray',
          })
          ->searchable(),
        Tables\Columns\TextColumn::make('type')
          ->label('Tipo')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('cellphone')
          ->label('Celular')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('date')
          ->label('Fecha')
          ->sortable()
          ->date()
          ->searchable(),
        Tables\Columns\TextColumn::make('price')
          ->label('Precio')
          ->sortable()
          ->formatStateUsing(function ($state) {
            return '$ ' . number_format($state, 0, ',', '.');
          })
          ->searchable(),
        Tables\Columns\TextColumn::make('smartphone')
          ->label('Equipo')
          ->sortable()
          ->default('-')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('imei')
          ->label('IMEI')
          ->sortable()
          ->default('-')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('iccid')
          ->label('ICCID')
          ->sortable()
          ->default('-')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('subdistributor')
          ->label('Subdistribuidor')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('notes')
          ->label('Notas')
          ->sortable()
          ->default('-')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('user.name')
          ->label('Usuario')
          ->sortable()
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Creado')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Actualizado')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->recordUrl(null)
      ->defaultSort('date', 'desc')
      ->filters([
        SelectFilter::make('status')
          ->label('Estado')
          ->options([
            'Pendiente' => 'Pendiente',
            'Pagada' => 'Pagada',
          ])
          ->native(false)
          ->preload()
          ->placeholder('Selecciona un estado'),
        SelectFilter::make('type')
          ->label('Tipo')
          ->options([
            'Plan' => 'Plan',
            'Repocisión' => 'Repocisión',
            'Kit financiado' => 'Kit financiado',
          ])
          ->native(false)
          ->preload()
          ->placeholder('Selecciona un tipo'),
      ])
      ->actions([
        ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->label('Ver')
            ->color('info')
            ->modalHeading('Detalles de la comisión')
            ->slideOver(),
          Action::make('pagar')
            ->label('Pagar')
            ->color('success')
            ->requiresConfirmation()
            ->modalSubmitActionLabel('Pagar')
            ->modalHeading('Pagar comisión')
            ->icon('heroicon-o-banknotes')
            ->modalIcon('heroicon-o-banknotes')
            ->modalDescription('¿Seguro que deseas pagar esta comisión? Ten en cuenta que al confirmar, no podrás deshacerla.')
            ->action(function ($record) {
              try {
                resolve(CommissionManager::class)->payCommission($record);
                Notification::make()
                  ->title('Comisión Pagada')
                  ->success()
                  ->send();
              } catch (\Exception $e) {
                Notification::make()
                  ->title('Error al pagar')
                  ->danger()
                  ->send();
              }
            })
            ->visible(fn($record) => $record->status !== 'Pagada'),
          DeleteAction::make()
            ->modalDescription('¿Seguro que deseas borrar esta comisión? Ten en cuenta que al hacerlo, no podrás recuperarla.')
            ->action(function ($record) {
              try {
                resolve(CommissionManager::class)->deleteCommission($record);
                Notification::make()
                  ->title('Comisión borrada')
                  ->success()
                  ->send();
              } catch (\Exception $e) {
                Notification::make()
                  ->title('Error al borrar')
                  ->danger()
                  ->send();
              }
            })
            ->visible(fn($record) => $record->status !== 'Pagada'),
        ])
          ->icon('heroicon-o-ellipsis-horizontal-circle')
          ->tooltip('Acciones')
          ->size(ActionSize::Large),
      ])
      ->actionsPosition(ActionsPosition::BeforeColumns)
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          // Tables\Actions\DeleteBulkAction::make(), // !TODO: Implementar exportacion de clientes
        ]),
      ]);
  }
}
