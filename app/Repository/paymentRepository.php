<?php
namespace App\Repository;

use App\Interfaces\paymentInterface;
use Illuminate\Http\Request;
use App\Models\UserAction;
use App\Models\Payment;
use App\Models\User;
use App\Models\contributionGroup;
use App\Models\contribution_payment;


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

        return response()->json([
            'message' => 'Deposit successful',
            'payment' => $payment
        ]);
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

        $memberIds = contributionGroup::find($contribution_group_id)->members->pluck('id')->toArray();
        if (!in_array($user->id, $memberIds)) {
            UserAction::create([
                'user_action' => "unauthorized contribution attempt of amount: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'You are not a member of this Contribution Group'], 403);
        }


        $payment = Payment::create([
            'user_id' => $user->id,
            'type' => 'contribution',
            'status' => 'successful',
            'amount' => $request->amount,
        ]);

        $contribute = contributionGroup::find($contribution_group_id);

        if (!$contribute) {
            UserAction::create([
                'user_action' => "made contribution to a deleted Contribution Group of amount: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Contribution Group not found'], 404);
        }

        if ($request->amount != $contribute->individualAmount) {
            UserAction::create([
                'user_action' => "made contribution with incorrect amount: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Incorrect contribution amount'], 400);
        }

        $paidContribution = contribution_payment::where('contribution_group_id', $contribution_group_id)
            ->where('user_id', $user->id)
            ->where('had_paid', false)
            ->first();

        if (!$paidContribution) {
            UserAction::create([
                'user_action' => "made contribution but failed because schedule not set or payment compelted: " . $request->amount,
                'user_id' => $user->id,
            ]);
            return response()->json(['message' => 'Contribution payment not scheduled or payment completed'], 401);
        }

        $paidContribution->had_paid = true;
        $paidContribution->save();




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

        return response()->json([
            'message' => 'withdrawal successful',
            'payment' => $payment
        ]);
    }

    public function getPaymentHistory(Request $request)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "checked payment history",
            'user_id' => $user->id,
        ]);

        $payment = Payment::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'message' => 'Payment history fetched successfully',
            'payment' => $payment
        ]);
    }

    public function checkBalance(Request $request)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "checked balance",
            'user_id' => $user->id,
        ]);
        $balance = User::find($request->user()->id)->balance;
        return response()->json([
            'message' => 'Balance checked successfully',
            'balance' => $balance
        ]);
    }

    public function checkContributionHistory(Request $request, $contribution_group_id)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "checked contribution history for group id: " . $contribution_group_id,
            'user_id' => $user->id,
        ]);

        $contributionPayments = contribution_payment::where('contribution_group_id', $contribution_group_id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Contribution history fetched successfully',
            'contribution_payments' => $contributionPayments
        ]);
    }
}