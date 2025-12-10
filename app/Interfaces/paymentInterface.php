<?php

namespace App\Interfaces;
use Illuminate\Http\Request;


interface paymentInterface
{
    //
    public function pay(Request $request, $contribution_group_id);

    public function deposit(Request $request);

    public function withdraw(Request $request);

    public function getPaymentHistory(Request $request);

    public function checkBalance(Request $request);
}