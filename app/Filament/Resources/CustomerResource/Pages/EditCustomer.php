<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Services\Customer\CustomerManager;
use Exception;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditCustomer extends EditRecord
{
  protected static string $resource = CustomerResource::class;

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

  /**
   * @throws ValidationException|Exception
   */
  protected function handleRecordUpdate(Model $record, array $data): Model

  {
    try {
      return resolve(CustomerManager::class)->updateCustomer($record, $data);
    } catch (Exception $e) {
      Notification::make()
        ->title('Error al actualizar')
        ->danger()
        ->send();
      throw $e;
    }
  }

  protected function getSavedNotificationTitle(): ?string
  {
    return 'Cliente editado';
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
