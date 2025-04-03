<?php

namespace App\Services\Customer;

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
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class CustomerTableBuilder
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
        Tables\Columns\TextColumn::make('document_type')
          ->label('Tipo')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('document_number')
          ->label('Documento')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('name')
          ->label('Nombre')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('cellphone')
          ->label('Celular')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('email')
          ->label('Correo')
          ->sortable()
          ->searchable(),
        Tables\Columns\TextColumn::make('street')
          ->label('Dirección')
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
      ->defaultSort('created_at', 'desc')
      ->filters([
        //
      ])
      ->actions([
        ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->label('Ver')
            ->color('info')
            ->modalHeading('Detalles del cliente')
            ->slideOver(),
          Tables\Actions\EditAction::make()->color('warning'),
          DeleteAction::make()
            ->modalDescription('¿Seguro que deseas borrar este cliente? Ten en cuenta que al hacerlo, no podrás recuperarlo.')
            ->action(function ($record) {
              try {
                resolve(CustomerManager::class)->deleteCustomer($record);
                Notification::make()
                  ->title('Cliente borrado')
                  ->success()
                  ->send();
              } catch (\Exception $e) {
                Notification::make()
                  ->title('Error al borrar')
                  ->danger()
                  ->send();
              }
            }),
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
