<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Services\Customer\CustomerFormBuilder;
use App\Services\Customer\CustomerTableBuilder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
  protected static ?string $model = Customer::class;

  protected static ?string $navigationIcon = 'heroicon-o-users';
  protected static $title = 'Clientes';
  protected static ?string $label = 'cliente';
  protected static ?string $pluralLabel = 'clientes';
  protected static ?string $slug = 'clientes';

  /**
   * Defines the form schema for the Customer resource.
   *
   * @param Form 
   * @return Form 
   */
  public static function form(Form $form): Form
  {
    return CustomerFormBuilder::form($form);
  }


  /**
   * Configures the table for the CustomerResource using a custom table builder.
   *
   * @param Table
   * @return Table 
   */
  public static function table(Table $table): Table
  {
    return CustomerTableBuilder::table($table);
  }

  /**
   * Get the relations for the resource.
   *
   * @return array An array of relationships.
   */
  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  /**
   * Defines the routes for the CustomerResource pages.
   *
   * @return array 
   */
  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCustomers::route('/'),
      'create' => Pages\CreateCustomer::route('/crear'),
      'edit' => Pages\EditCustomer::route('/editar/{record}'),
    ];
  }
}
