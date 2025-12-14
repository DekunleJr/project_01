<?php

namespace App\Http\Controllers;

use App\Models\payment;

use Illuminate\Http\Request;
class PaymentController extends Controller
{
    public $paymentRepository;
    public function __construct(\App\Interfaces\paymentInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function deposit(Request $request)
    {
        return $this->paymentRepository->deposit($request);
    }

    public function pay(Request $request)
    {
        return $this->paymentRepository->pay($request, $request->contribution_group_id);
    }

    // public function pay(Request $request, $contribution_group_id)
    // {
    //     $request->merge(['contribution_group_id' => $contribution_group_id]); // Pass it to the request for the repository
    //     return $this->paymentRepository->pay($request);
    // }


    public function withdraw(Request $request)
    {
        return $this->paymentRepository->withdraw($request);
    }

    public function getPaymentHistory(Request $request)
    {
        return $this->paymentRepository->getPaymentHistory($request);
    }

    public function checkBalance(Request $request)
    {
        return $this->paymentRepository->checkBalance($request);
    }

    public function checkContributionHistory(Request $request, $contribution_group_id)
    {
        return $this->paymentRepository->checkContributionHistory($request, $contribution_group_id);
    }

}
