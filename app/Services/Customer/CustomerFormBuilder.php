<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;

class CustomerFormBuilder
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
    return Section::make('Información del cliente')
      ->columns([
        'default' => 1,
        'md' => 2,
      ])
      ->schema([
        Grid::make([
          'default' => 1,
          'sm' => 1,
          'md' => 12,
        ]),
        static::getDocumentTypeFormField(),
        static::getDocumentFormField(),
        static::getNameFormField(),
        static::getEmailFormField(),
        static::getCellphoneFormField(),
        static::getStreetFormField(),
      ]);
  }

  /**
   * Return document type form field.
   * 
   * @return Select
   */
  public static function getDocumentTypeFormField(): Select
  {
    return Select::make('document_type')
      ->label('Tipo de documento')
      ->options([
        'CC' => 'Cédula de ciudadanía',
        'NIT' => 'NIT',
        'TI' => 'Tarjeta de identidad',
        'CE'  => 'Cédula de extranjería',
        'PA' => 'Pasaporte',
      ])
      ->placeholder('Selecciona un tipo de documento')
      ->native(false)
      ->required()
      ->validationMessages([
        'required' => 'El tipo de documento es obligatorio',
      ]);
  }

  /**
   * Return document form field.
   * 
   * @return TextInput
   */
  public static function getDocumentFormField(): TextInput
  {
    return TextInput::make('document_number')
      ->label('Número de documento')
      ->required()
      ->unique(ignoreRecord: true)
      ->autocomplete(false)
      ->autofocus()
      ->numeric()
      ->maxLength(30)
      ->minLength(5)
      ->reactive()
      ->afterStateUpdated(function ($state, $set, $get) {
        $customer = Customer::where('document_number', $state)->first();
        if ($customer && $get('id') !== $customer->id) {
          Notification::make()
            ->title('Documento ya registrado')
            ->warning()
            ->send();
        }
      })
      ->validationMessages([
        'required' => 'El número de documento es obligatorio',
        'unique' => 'El número de documento ya está registrado',
        'numeric' => 'El número de documento debe ser numérico',
        'min_length' => 'El número de documento debe tener al menos 5 dígitos',
        'max_length' => 'El número de documento no puede tener más de 30 dígitos',
      ]);
  }


  /**
   * Return name form field.
   * 
   * @return TextInput
   */
  public static function getNameFormField(): TextInput
  {
    return TextInput::make('name')
      ->label('Nombre')
      ->required()
      ->string()
      ->minLength(5)
      ->maxLength(100)
      ->autocomplete(false)
      ->validationMessages([
        'required' => 'El nombre es obligatorio',
        'min_length' => 'El nombre debe tener al menos 5 caracteres',
        'max_length' => 'El nombre no debe exceder los 100 caracteres',
      ]);
  }

  /**
   * Return email form field.
   * 
   * @return TextInput
   */
  public static function getEmailFormField(): TextInput
  {
    return TextInput::make('email')
      ->label('Correo electrónico')
      ->email()
      ->required()
      ->maxLength(50)
      ->default('notiene@maddox.com')
      ->autocomplete(false)
      ->unique(ignoreRecord: true)
      ->reactive()
      ->afterStateUpdated(function ($state) {
        if (Customer::where('email', $state)->exists()) {
          Notification::make()
            ->title('Correo en uso')
            ->warning()
            ->send();
        }
      })
      ->validationMessages([
        'required' => 'El correo electrónico es obligatorio',
        'email' => 'El correo electrónico no es válido',
        'max_length' => 'El correo electrónico no debe exceder los 50 caracteres',
        'unique' => 'El correo electrónico ya está en uso',
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
   * Return street form field.
   * 
   * @return TextInput
   */
  public static function getStreetFormField(): TextInput
  {
    return TextInput::make('street')
      ->label('Dirección')
      ->maxLength(255)
      ->autocomplete(false)
      ->validationMessages([
        'max_length' => 'La dirección no debe exceder los 255 caracteres',
      ]);
  }
}
