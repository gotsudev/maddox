<?php

namespace App\Services\Commission;

use App\Services\Customer\CustomerFormBuilder;
use App\Services\Customer\CustomerManager;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Support\RawJs;

class CommissionFormBuilder
{
  /**
   * Return form schema for customer.
   *
   * @param Form $form
   * @return Form
   */
  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        static::getCustomerFormSchema(),
      ]);
  }

  /**
   * Return form schema for customer.
   * 
   * @return array
   */
  public static function customerFormSchema(): array
  {
    return [
      static::getCustomerFormSchema()
    ];
  }

  /**
   * Return customer form schema.
   * 
   * @return Section
   */
  public static function getCustomerFormSchema(): Section
  {
    return Section::make('Información de la comisión')
      ->columns([
        'default' => 1,
        'md' => 2,
      ])
      ->schema([
        static::getCustomerFormField(),
        static::getDocumentTypeFormField(),
        static::getSmartPhoneFormField(),
        static::getIMEIFormField(),
        static::getCellphoneFormField(),
        static::getICCIDFormField(),
        static::getDateFormField(),
        static::getPriceFormField(),
        static::getNoteFormField(),
      ]);
  }

  /**
   * Return document type form field.
   * 
   * @return Select
   */
  public static function getDocumentTypeFormField(): Select
  {
    return Select::make('type')
      ->label('Tipo de comisión')
      ->options([
        'Plan' => 'Plan',
        'Repocisión' => 'Repocisión',
        'Kit financiado' => 'Kit financiado',
      ])
      ->placeholder('Selecciona un tipo de comisión')
      ->native(false)
      ->required()
      ->reactive()
      ->validationMessages([
        'required' => 'El tipo de comisión es obligatorio',
      ]);
  }

  /**
   * Get customer form field.
   *
   * @return Select
   */
  public static function getCustomerFormField(): Select
  {
    return Select::make('customer_id')
      ->label('Cliente')
      ->relationship('customer', 'name')
      ->placeholder('Selecciona un cliente')
      ->native(false)
      ->searchable()
      ->preload()
      ->required()
      ->createOptionForm(CustomerFormBuilder::customerFormSchema())
      ->createOptionUsing(fn(array $data) => resolve(CustomerManager::class)->createCustomer($data)->id)
      ->createOptionAction(function (Action $action) {
        return $action
          ->modalHeading('Crear cliente')
          ->modalSubmitActionLabel('Crear')
          ->slideOver()
          ->closeModalByClickingAway(false);
      })
      ->validationMessages([
        'required' => 'El cliente es obligatorio',
      ]);
  }


  /**
   * Return cellphone form field.
   * 
   * @return TextInput
   */
  public static function getCellphoneFormField(): TextInput
  {
    return TextInput::make('cellphone')
      ->label('Celular')
      ->tel()
      ->required()
      ->maxLength(10)
      ->minLength(10)
      ->autocomplete(false)
      ->validationMessages([
        'required' => 'El celular es obligatorio',
        'max_length' => 'El celular debe tener 10 dígitos',
        'min_length' => 'El celular debe tener 10 dígitos',
      ]);
  }



  /**
   * Return smaerphone form field.
   * 
   * @return TextInput
   */
  public static function getSmartPhoneFormField(): TextInput
  {
    return TextInput::make('smartphone')
      ->label('Equipo')
      ->required()
      ->string()
      ->minLength(5)
      ->maxLength(100)
      ->autocomplete(false)
      ->required(fn($get) => $get('type') === 'Kit financiado' || $get('type') === 'Repocisión')
      ->visible(fn($get) => $get('type') === 'Kit financiado' || $get('type') === 'Repocisión')
      ->validationMessages([
        'required' => 'El equipo es obligatorio',
        'min_length' => 'El equipo debe tener al menos 5 caracteres',
        'max_length' => 'El equipo no debe exceder los 100 caracteres',
      ]);
  }

  /**
   * Return imei form field.
   * 
   * @return TextInput
   */
  public static function getIMEIFormField(): TextInput
  {
    return TextInput::make('imei')
      ->label('IMEI')
      ->required()
      ->string()
      ->minLength(15)
      ->maxLength(15)
      ->autocomplete(false)
      ->required(fn($get) => $get('type') === 'Kit financiado' || $get('type') === 'Repocisión')
      ->visible(fn($get) => $get('type') === 'Kit financiado' || $get('type') === 'Repocisión')
      ->validationMessages([
        'required' => 'El IMEI es obligatorio',
        'min_length' => 'El IMEI debe tener al menos 15 caracteres',
        'max_length' => 'El IMEI no debe exceder los 15 caracteres',
      ]);
  }

  /**
   * Return smaerphone form field.
   * 
   * @return TextInput
   */
  public static function getICCIDFormField(): TextInput
  {
    return TextInput::make('iccid')
      ->label('ICCID')
      ->required()
      ->string()
      ->numeric()
      ->minLength(15)
      ->maxLength(30)
      ->required(fn($get) => $get('type') === 'Kit financiado')
      ->visible(fn($get) => $get('type') === 'Kit financiado')
      ->autocomplete(false)
      ->validationMessages([
        'required' => 'El ICCID es obligatorio',
        'min_length' => 'El ICCID debe tener al menos 15 caracteres',
        'max_length' => 'El ICCID no debe exceder los 30 caracteres',
        'numeric' => 'El ICCID debe ser numérico',
      ]);
  }

  /**
   * Get date form field.
   *
   * @return DatePicker
   */
  public static function getDateFormField(): DatePicker
  {
    return DatePicker::make('date')
      ->label('Fecha')
      ->required()
      ->closeOnDateSelection()
      ->displayFormat('d/m/Y')
      ->firstDayOfWeek(7)
      ->native(false)
      ->validationMessages([
        'required' => 'La fecha de vencimiento es obligatoria',
      ]);
  }

  /**
   * Get price form field.
   *
   * @return TextInput
   */
  public static function getPriceFormField(): TextInput
  {
    return TextInput::make('price')
      ->label('Precio')
      ->required()
      ->autocomplete(false)
      ->numeric()
      ->minValue(1)
      ->mask(RawJs::make('$money($input, \',\', \'.\')'))
      ->stripCharacters('.')
      ->validationMessages([
        'required' => 'El precio es obligatorio',
        'numeric' => 'El precio debe ser un número',
        'min_value' => 'El precio no puede ser $ 0 ',
      ]);
  }

  /**
   * Get note form field.
   *
   * @return Textarea
   */
  public static function getNoteFormField(): Textarea
  {
    return Textarea::make('notes')
      ->label('Notas')
      ->autocomplete(false)
      ->string()
      ->maxLength(255)
      ->columnSpanFull()
      ->validationMessages([
        'max_length' => 'La nota no debe exceder los 255 caracteres',
      ]);
  }
}
