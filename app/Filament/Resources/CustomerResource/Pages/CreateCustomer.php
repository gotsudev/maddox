<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Services\Customer\CustomerManager;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateCustomer extends CreateRecord
{
  protected static string $resource = CustomerResource::class;

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }

  protected function handleRecordCreation(array $data): Model
  {
    try {
      return resolve(CustomerManager::class)->createCustomer($data);
    } catch (Exception $e) {
      Notification::make()
        ->title('Error al crear cliente')
        ->body($e->getMessage())
        ->danger()
        ->send();
      throw $e;
    }
  }

  protected function getCreatedNotificationTitle(): ?string
  {
    return 'Cliente creado';
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
