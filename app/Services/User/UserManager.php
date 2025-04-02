<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserManager
{


  public function deleteUser(User $user): void
  {
    $user->delete();
  }
  /**
   * Update the user's password.
   *
   * @param User $user
   * @param string $password
   * @return User
   */
  public function updatePassword(User $user, string $password): User
  {
    $user->password = bcrypt($password);
    $user->save();

    return $user;
  }

  /**
   * Inactive a user.
   *
   * @param User $user
   * @return User
   */
  public function inactiveUser(User $user): bool
  {
    return DB::transaction(function () use ($user) {
      // Eliminar las sesiones del usuario
      DB::table('sessions')->where('user_id', $user->id)->delete();
      // Actualizar el estado del usuario
      $user->status = 'Inactivo';
      return $user->save();
    });
  }

  /**
   * Active a user.
   *
   * @param User $user
   * @return User
   */
  public function activeUser(User $user): User
  {
    $user->status = 'Activo';
    $user->save();

    return $user;
  }
}
