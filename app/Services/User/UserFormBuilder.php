<?php

namespace App\Services\User;

use App\Models\Customer;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;

class UserFormBuilder
{
  /**
   * Return form schema for user.
   *
   * @param Form $form
   * @return Form
   */
  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        static::getUserFormSchema(),
      ]);
  }

  /**
   * Return form schema for user.
   * 
   * @return array
   */
  public static function userFormSchema(): array
  {
    return [
      static::getUserFormSchema()
    ];
  }

  /**
   * Return user form schema.
   * 
   * @return Section
   */
  public static function getUserFormSchema(): Section
  {
    return Section::make('Información del usuario')
      ->columns([
        'default' => 1,
        'md' => 2,
      ])
      ->schema([
        static::getNameFormField(),
        static::getEmailFormField(),
        static::getCellphoneFormField(),
        static::getStatusFormField(),
        // static::getRolFormField(),
        static::getPasswordFormField(),
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
      ->label('Nombres')
      ->required()
      ->string()
      ->minLength(5)
      ->maxLength(100)
      ->autofocus()
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
      ->autocomplete(false)
      ->unique(ignoreRecord: true)
      ->reactive()
      ->afterStateUpdated(function ($state) {
        if (User::where('email', $state)->exists()) {
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
        'max_length' => 'El celular debe tener 10 caracteres',
        'min_length' => 'El celular debe tener 10 caracteres',
      ]);
  }

  /**
   * Return street form field.
   * 
   * @return TextInput
   */
  public static function getStatusFormField(): Select
  {
    return Select::make('status')
      ->label('Estado')
      ->options([
        'Activo' => 'Activo',
        'Inactivo' => 'Inactivo',
      ])
      ->default('Activo')
      ->native(false)
      ->required()
      ->validationMessages(
        [
          'required' => 'El estado es obligatorio',
        ]
      );
  }

  /**
   * Return password form field.
   * 
   * @return TextInput
   */
  public static function getPasswordFormField(): TextInput
  {
    return TextInput::make('password')
      ->label('Contraseña')
      ->password()
      ->required()
      ->revealable()
      ->minLength(6)
      ->maxLength(50)
      ->hidden(fn($context) => $context === 'edit' || $context === 'view')
      ->validationMessages([
        'required' => 'La contraseña es obligatoria',
        'min_length' => 'La contraseña debe tener al menos 6 caracteres',
        'max_length' => 'La contraseña no debe exceder los 50 caracteres',
      ]);
  }

  /**
   * Return rol form field.
   * 
   * @return Select
   */
  public static function getRolFormField(): Select
  {
    return Select::make('roles')
      ->relationship('roles', 'name')
      ->label('Rol')
      ->native(false)
      ->required()
      ->preload()
      ->placeholder('Selecciona un rol')
      ->validationMessages([
        'required' => 'El rol es obligatorio',
      ]);
  }
}
