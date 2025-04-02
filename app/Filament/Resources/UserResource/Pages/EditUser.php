<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditUser extends EditRecord
{
  protected static string $resource = UserResource::class;

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }

  protected function getHeaderActions(): array
  {
    return [
      // Actions\DeleteAction::make(),
    ];
  }

  protected function getSavedNotificationTitle(): ?string
  {
    return 'Usuario editado';
  }

  protected function onValidationError(ValidationException $exception): void
  {
    Notification::make()
      ->title('Error de validación')
      ->body($exception->getMessage())
      ->danger()
      ->send();
  }
}
