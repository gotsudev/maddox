<?php

namespace App\Services\Commission;

use App\Models\Commission;
use Illuminate\Support\Facades\Auth;

class CommissionManager
{

  /**
   * Create a new commission.
   *
   * @param array<string, mixed> $data
   * @return Commission
   */
  public function createCommission(array $data): Commission
  {
    $commission = new Commission();
    $data['user_id'] = Auth::id();
    $commission->fill($data);
    $commission->save();
    return $commission;
  }

  /**
   * Delete a commission.
   *
   * @param Commission $commission
   * @return void
   */
  public function deleteCommission(Commission $commission): void
  {
    $commission->delete();
  }


  public function payCommission(Commission $commission): void
  {
    $commission->status = 'Pagada';
    $commission->save();
  }
}
