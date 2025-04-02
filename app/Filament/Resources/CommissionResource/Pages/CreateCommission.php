<?php

namespace App\Filament\Resources\CommissionResource\Pages;

use App\Filament\Resources\CommissionResource;
use App\Services\Commission\CommissionManager;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateCommission extends CreateRecord
{
  protected static string $resource = CommissionResource::class;

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }

  protected function handleRecordCreation(array $data): Model
  {
    try {
      return resolve(CommissionManager::class)->createCommission($data);
    } catch (Exception $e) {
      Notification::make()
        ->title('Error al crear comisión')
        ->body($e->getMessage())
        ->danger()
        ->send();
      throw $e;
    }
  }

  protected function getCreatedNotificationTitle(): ?string
  {
    return 'Comisión creada';
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
