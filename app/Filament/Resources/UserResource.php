<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Services\User\UserFormBuilder;
use App\Services\User\UserInfoListBuilder;
use App\Services\User\UserTableBuilder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-user';
  protected static $title = 'Usuarios';
  protected static ?string $label = 'usuario';
  protected static ?string $pluralLabel = 'usuarios';
  protected static ?string $slug = 'usuarios';

  /**
   * Defines the form schema for the User resource.
   *
   * @param Form 
   * @return Form 
   */
  public static function form(Form $form): Form
  {
    return UserFormBuilder::form($form);
  }

  /**
   * Configures the table for the UserResource using a custom table builder.
   *
   * @param Table
   * @return Table 
   */
  public static function table(Table $table): Table
  {
    return UserTableBuilder::table($table);
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
   * Get the infolist for the resource.
   * 
   * @param Infolist $infolist
   * @return Infolist
   */
  public static function infolist(Infolist $infolist): Infolist
  {
    return UserInfoListBuilder::infolist($infolist);
  }

  /**
   * Defines the routes for the UserResource pages.
   *
   * @return array 
   */
  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/crear'),
      'edit' => Pages\EditUser::route('/editar/{record}'),
    ];
  }
}
