<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'customer_id',
    'user_id',
    'cellphone',
    'date',
    'type',
    'smartphone',
    'imei',
    'iccid',
    'price',
    'status',
    'subdistributor',
    'notes',
  ];

  /**
   * Attributes that must be mutated before saving.
   *
   * @var array<string>
   */
  public function setNotesAttribute($value)
  {
    $this->attributes['notes'] = strtoupper($value);
  }

  public function setSubdistributorAttribute($value)
  {
    $this->attributes['subdistributor'] = strtoupper($value);
  }

  /**
   * Relations with other models.
   * 
   */

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
