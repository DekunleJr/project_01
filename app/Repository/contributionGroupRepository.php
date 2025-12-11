<?php
namespace App\Repository;

use App\Interfaces\contributionGroupInterface;
use Illuminate\Http\Request;
use App\Models\UserAction;
use App\Models\Payment;
use App\Models\User;
use App\Models\contributionGroup;


class contributionGroupRepository implements contributionGroupInterface
{
    //
    public function getAllContributionGroups(Request $request)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "fetched all contribution groups",
            'user_id' => $user->id,
        ]);
        $group = contributionGroup::all();

        return response()->json([
            'message' => 'Contribution Groups fetched successfully',
            'contribution_groups' => $group,
        ]);
    }

    public function getContributionGroupById(Request $request, $id)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "fetched contribution group by id: " . $id,
            'user_id' => $user->id,
        ]);

        $group = contributionGroup::find($id);
        return response()->json([
            'message' => 'Contribution Group fetched successfully',
            'contribution_group' => $group,
        ]);
    }

    public function createContributionGroup(Request $request)
    {
        $request->validate([
            'individualAmount' => 'required|numeric|min:1',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'frequency' => 'required|string|in:weekly,monthly,yearly',
        ]);

        $user = $request->user();

        $contributionGroup = contributionGroup::create([
            'title' => $request->title,
            'individualAmount' => $request->individualAmount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'frequency' => $request->frequency,
            'users' => [],
            'amount' => 0,
        ]);

        UserAction::create([
            'user_action' => "made contribution but failed because schedule not set or payment compelted: " . $request->amount,
            'user_id' => $user->id,
        ]);
        return response()->json([
            'message' => 'Group created successfully successful',
            'Contribution_Group' => $contributionGroup,
        ]);
    }

    public function updateContributionGroup(Request $request, $id)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "fetched all contribution groups",
            'user_id' => $user->id,
        ]);

        $request->validate([
            'individualAmount' => 'required|numeric|min:1',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'frequency' => 'required|string|in:weekly,monthly,yearly',
        ]);

        $group = contributionGroup::find($id);
        $group->update($request->all());

        return response()->json([
            'message' => 'Contribution Group updated successfully',
            'contribution_group' => $group,
        ]);
    }

    // public function assignMembersToGroup(Request $request, $groupId)
    // {
    //     $request->validate([
    //         'user_ids' => 'required|array',
    //         'user_ids.*' => 'exists:users,id',
    //     ]);

    //     $group = contributionGroup::find($groupId);
    //     $userIds = $request->input('user_ids', []);
    //     if (!$group) {
    //         return response()->json(['message' => 'Contribution Group not found'], 404);
    //     }

    //     $group->users = $userIds;
    //     $group->save();

    //     $user = $request->user();
    //     UserAction::create([
    //         'user_action' => "fetched all contribution groups",
    //         'user_id' => $user->id,
    //     ]);
    //     return response()->json([
    //         'message' => 'Members assigned successfully',
    //         'contribution_group' => $group,
    //         'users' => $userIds,
    //     ]);
    // }

    public function assignMembersToGroup(Request $request, $groupId)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $group = contributionGroup::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Contribution Group not found'], 404);
        }

        $newUserIds = $request->input('user_ids', []);

        // --- Merge without deleting previous ones ---
        $existingUsers = $group->users ?? [];   // existing array in DB

        // Merge & remove duplicates
        $mergedUsers = array_values(array_unique(array_merge($existingUsers, $newUserIds)));

        $group->users = $mergedUsers;
        $group->save();

        // Log action
        $user = $request->user();
        UserAction::create([
            'user_action' => "added members to contribution group {$groupId}",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Members added successfully',
            'contribution_group' => $group,
            'users' => $mergedUsers,
        ]);
    }

    public function removeMembersFromGroup(Request $request, $groupId)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $group = contributionGroup::find($groupId);
        $userIds = $request->input('user_ids', []);
        if (!$group) {
            return response()->json(['message' => 'Contribution Group not found'], 404);
        }

        foreach ($userIds as $id) {
            if (!in_array($id, $group->users)) {
                return response()->json(['message' => 'User with id ' . $id . ' is not a member of the group'], 400);
            }
        }

        $group->users = array_diff($group->users, $userIds);
        $group->save();

        $user = $request->user();
        UserAction::create([
            'user_action' => "Removed users from group with id:" . $groupId,
            'user_id' => $user->id,
        ]);
        return response()->json([
            'message' => 'Members removed successfully',
            'contribution_group' => $group,
            'users' => $userIds,
        ]);
    }
    public function payOut(Request $request, $groupId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'user_id' => 'required|exists:users,id',
        ]);

        $group = contributionGroup::find($groupId);
        if (!$group) {
            return response()->json(['message' => 'Contribution Group not found'], 404);
        }

        if ($request->amount > $group->amount) {
            return response()->json(['message' => 'Insufficient funds in the Contribution Group'], 400);
        }


        $userId = $request->input('user_id');
        if (!in_array($userId, $group->users)) {
            return response()->json(['message' => 'User is not a member of the group'], 400);
        }

        $payoutUser = User::find($userId);
        $payoutUser->balance += $request->amount;
        $payoutUser->save();

        $group->amount -= $request->amount;
        $group->save();

        $user = $request->user();
        UserAction::create([
            'user_action' => "fetched all contribution groups",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Payout successful',
            'contribution_group' => $group,
            'payout_user' => $payoutUser,
        ]);
    }

    public function deleteContributionGroup(Request $request, $id)
    {
        $user = $request->user();
        UserAction::create([
            'user_action' => "fetched all contribution groups",
            'user_id' => $user->id,
        ]);

        $group = contributionGroup::find($id);
        if (!$group) {
            return response()->json(['message' => 'Contribution Group not found'], 404);
        }
        $group->delete();
        return response()->json(['message' => 'Contribution Group deleted successfully']);
    }
}