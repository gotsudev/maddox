<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'document_type',
    'document_number',
    'name',
    'email',
    'cellphone',
    'street',
  ];


  /**
   * Attributes that must be mutated before saving.
   *
   * @var array<string>
   */

  public function setNameAttribute($value)
  {
    $this->attributes['name'] = strtoupper($value);
  }

  public function setEmailAttribute($value)
  {
    $this->attributes['email'] = strtolower($value);
  }

  public function setStreetAttribute($value)
  {
    $this->attributes['street'] = strtoupper($value);
  }
}
