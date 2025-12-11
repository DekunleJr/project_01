<?php

namespace App\Interfaces;

interface adminInterface
{
    //
    public function getUsers();

    public function getUserById($id);

    public function updateUserRole($id);

    public function deleteUser($id);

    public function getPayments();

    public function getPaymentById($id);


}
