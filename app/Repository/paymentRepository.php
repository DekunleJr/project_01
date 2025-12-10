<?php
namespace App\Repository;

use App\Interfaces\paymentInterface;
use Illuminate\Http\Request;
use App\Models\UserAction;
use App\Models\Payment;
use App\Models\User;
use App\Models\contributionGroup;


class paymentRepository implements paymentInterface
{
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);


        $user = $request->user();


        UserAction::create([
            'user_action' => "Deposited: " . $request->amount,
            'user_id' => $user->id,
        ]);

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'status' => 'successful',
            'amount' => $request->amount,
        ]);

        // Update user balance
        $user->balance = $user->balance + $request->amount;
        $user->save();

        return $payment;
    }

    public function pay(Request $request, $contribution_group_id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = $request->user();

        if ($user->balance < $request->amount) {
            UserAction::create([
                'user_action' => "contribution failed of amount: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Insufficient balance'], 400);
        }


        $payment = Payment::create([
            'user_id' => $user->id,
            'type' => 'contribution',
            'status' => 'successful',
            'amount' => $request->amount,
        ]);

        $contribute = contributionGroup::find($request->contribution_group_id);

        if (!$contribute) {
            UserAction::create([
                'user_action' => "made contribution to a deleted Contribution Group of amount: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Contribution Group not found'], 404);
        }

        $contribute->amount += $request->amount;
        $contribute->save();

        $group = $contribute->title;

        $user->balance -= $request->amount;
        $user->save();

        UserAction::create([
            'user_action' => "contribution made of amount: " . $request->amount,
            'user_id' => $user->id,
        ]);
        return response()->json([
            'message' => 'Payment successful',
            'Contribution_Group' => $group,
            'payment' => $payment
        ]);
    }


    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);


        $user = $request->user();

        if ($user->balance < $request->amount) {
            UserAction::create([
                'user_action' => "withdrawal failed of amount: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Insufficient balance'], 400);
        }


        UserAction::create([
            'user_action' => "Withdrawn: " . $request->amount,
            'user_id' => $user->id,
        ]);

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'status' => 'successful',
            'amount' => $request->amount,
        ]);

        // Update user balance
        $user->balance = $user->balance - $request->amount;
        $user->save();

        return $payment;
    }

    public function getPaymentHistory(Request $request)
    {
        return payment::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
    }

    public function checkBalance(Request $request)
    {
        return User::find($request->user()->id)->balance;
    }
}