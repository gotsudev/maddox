<?php

namespace App\Services\User;

use Exception;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserTableBuilder
{
  /**
   * Configure the user table.
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
        Tables\Columns\TextColumn::make('name')
          ->label('Nombre')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('email')
          ->label('Correo electrónico')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('cellphone')
          ->label('Celular')
          ->searchable()
          ->sortable(),
        /* Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->searchable(), */
        Tables\Columns\TextColumn::make('status')
          ->label('Estado')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'Activo' => 'success',
            'Inactivo' => 'warning',
          })
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('email_verified_at')
          ->label('Correo verificado')
          ->dateTime()
          ->sortable()
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
        SelectFilter::make('status')
          ->label('Estado')
          ->options([
            'Activo' => 'Activo',
            'Inactivo' => 'Inactivo',
          ])
          ->native(false)
          ->preload()
          ->placeholder('Selecciona un estado'),
      ])
      ->actions([
        ActionGroup::make([
          Tables\Actions\ViewAction::make()
            ->label('Ver')
            ->color('primary')
            ->modalHeading('Detalles del usuario')
            ->slideOver(),
          Tables\Actions\EditAction::make()->color('warning'),
          Tables\Actions\Action::make('Activar usuario')
            ->label('Activar')
            ->color('success')
            ->icon('heroicon-m-shield-check')
            ->visible(fn($record) => $record->status === 'Inactivo')
            ->action(function ($record) {
              try {
                resolve(UserManager::class)->activeUser($record);
                Notification::make()
                  ->title('Usuario activado')
                  ->success()
                  ->send();
              } catch (Exception $e) {
                Notification::make()
                  ->title('Error al activar')
                  ->danger()
                  ->send();
              }
            }),
          Tables\Actions\Action::make('Desactivar usuario')
            ->label('Desactivar')
            ->color('warning')
            ->icon('heroicon-m-shield-exclamation')
            ->visible(fn($record) => $record->status === 'Activo')
            ->action(function ($record) {
              try {
                if ($record->id === Auth::id()) {
                  Notification::make()
                    ->title('No puedes desactivar tu propio usuario')
                    ->warning()
                    ->send();
                  return;
                }
                resolve(UserManager::class)->inactiveUser($record);
                Notification::make()
                  ->title('Usuario desactivado')
                  ->success()
                  ->send();
              } catch (Exception $e) {
                Notification::make()
                  ->title('Error al desactivar')
                  ->danger()
                  ->send();
              }
            }),
          Action::make('Cambiar contraseña')
            ->label('Actualizar')
            ->color('primary')
            ->icon('heroicon-o-finger-print')
            ->modalHeading('Actualizar contraseña')
            ->modalWidth(MaxWidth::Medium)
            ->modalSubmitActionLabel('Actualizar')
            ->slideOver()
            ->form([
              TextInput::make('password')
                ->password()
                ->revealable()
                ->required()
                ->label('Nueva contraseña'),
            ])
            ->action(function ($record, $data) {
              try {
                // Verificar si es mi usuario para no actualizar la contraseña
                if ($record->id === Auth::id()) {
                  Notification::make()
                    ->title('Actualiza tu contraseña desde tu perfil')
                    ->success()
                    ->icon('heroicon-o-information-circle')
                    ->persistent()
                    ->actions([
                      NotificationAction::make('Ir a mi perfil')
                        ->button()
                        ->url(route('filament.pos.pages.perfil')),
                    ])
                    ->send();
                  return;
                }
                resolve(UserManager::class)->updatePassword($record, $data['password']);
                Notification::make()
                  ->title('Contraseña actualizada')
                  ->success()
                  ->send();
              } catch (Exception $e) {
                Notification::make()
                  ->title('Error al actualizar contraseña')
                  ->danger()
                  ->send();
              }
            }),
          DeleteAction::make()
            ->modalDescription('¿Seguro que deseas borrar este usuario? Ten en cuenta que al hacerlo, no podrás recuperarlo.')
            ->action(function ($record) {
              try {
                // Verificar si es mi usuario para no borrarlo
                if ($record->id === Auth::id()) {
                  Notification::make()
                    ->title('No puedes borrar tu propio usuario')
                    ->warning()
                    ->send();
                  return;
                }
                resolve(UserManager::class)->deleteUser($record);
                Notification::make()
                  ->title('Usuario borrado')
                  ->success()
                  ->send();
              } catch (Exception $e) {
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
          // Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }
}
