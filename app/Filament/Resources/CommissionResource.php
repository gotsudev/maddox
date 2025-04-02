<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommissionResource\Pages;
use App\Filament\Resources\CommissionResource\RelationManagers;
use App\Models\Commission;
use App\Services\Commission\CommissionFormBuilder;
use App\Services\Commission\CommissionInfoListBuilder;
use App\Services\Commission\CommissionTableBuilder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommissionResource extends Resource
{
  protected static ?string $model = Commission::class;

  protected static ?string $navigationIcon = 'heroicon-o-sparkles';
  protected static $title = 'Comisiones';
  protected static ?string $label = 'comisión';
  protected static ?string $pluralLabel = 'comisiones';
  protected static ?string $slug = 'comisiones';

  /**
   * Defines the form schema for the Commission resource.
   *
   * @param Form 
   * @return Form 
   */
  public static function form(Form $form): Form
  {
    return CommissionFormBuilder::form($form);
  }


  /**
   * Configures the table for the CommissionResource using a custom table builder.
   *
   * @param Table
   * @return Table 
   */
  public static function table(Table $table): Table
  {
    return CommissionTableBuilder::table($table);
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
    return CommissionInfoListBuilder::infolist($infolist);
  }

  /**
   * Defines the routes for the CommissionResource pages.
   *
   * @return array 
   */
  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCommissions::route('/'),
      'create' => Pages\CreateCommission::route('/crear'),
    ];
  }
}
