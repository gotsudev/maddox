<?php

namespace App\Services\Customer;

use App\Models\Customer;

class CustomerManager
{

  /**
   * Create a new customer.
   *
   * @param array<string, mixed> $data
   * @return Customer
   */
  public function createCustomer(array $data): Customer
  {
    $customer = new Customer();
    $customer->fill($data);
    $customer->save();
    return $customer;
  }

  /**
   * Update a customer.
   *
   * @param Customer $customer
   * @param array<string, mixed> $data
   * @return Customer
   */

  public function updateCustomer(Customer $customer, array $data): Customer
  {
    if ($data['document_type'] != 'NIT') {
      $data['company'] = null;
      $data['dv'] = null;
    }
    $customer->fill($data);
    $customer->save();
    return $customer;
  }

  /**
   * Delete a customer.
   *
   * @param Customer $customer
   * @return void
   */
  public function deleteCustomer(Customer $customer): void
  {
    $customer->delete();
  }
}
