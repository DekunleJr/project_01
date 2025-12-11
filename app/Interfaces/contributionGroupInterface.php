<?php

namespace App\Interfaces;


use Illuminate\Http\Request;

interface contributionGroupInterface
{
    //
    public function getAllContributionGroups(Request $request);

    public function getContributionGroupById(Request $request, $id);

    public function createContributionGroup(Request $request);

    public function updateContributionGroup(Request $request, $id);

    public function assignMembersToGroup(Request $request, $groupId);

    public function removeMembersFromGroup(Request $request, $groupId);

    public function payOut(Request $request, $groupId);

    public function deleteContributionGroup(Request $request, $id);
}
