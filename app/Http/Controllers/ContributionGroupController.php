<?php

namespace App\Http\Controllers;

use App\Models\contributionGroup;
use App\Http\Requests\StorecontributionGroupRequest;
use App\Http\Requests\UpdatecontributionGroupRequest;
use Illuminate\Http\Request;

class ContributionGroupController extends Controller
{
    public $contributionGroupRepository;
    public function __construct(\App\Interfaces\contributionGroupInterface $contributionGroupRepository)
    {
        $this->contributionGroupRepository = $contributionGroupRepository;
    }

    public function getAllContributionGroups(Request $request)
    {
        return $this->contributionGroupRepository->getAllContributionGroups($request);
    }

    public function getContributionGroupById(Request $request, $id)
    {
        return $this->contributionGroupRepository->getContributionGroupById($request, $id);
    }

    public function createContributionGroup(Request $request)
    {
        return $this->contributionGroupRepository->createContributionGroup($request);
    }

    public function updateContributionGroup(Request $request, $id)
    {
        return $this->contributionGroupRepository->updateContributionGroup($request, $id);
    }

    public function assignMembersToGroup(Request $request, $groupId)
    {
        return $this->contributionGroupRepository->assignMembersToGroup($request, $groupId);
    }

    public function removeMembersFromGroup(Request $request, $groupId)
    {
        return $this->contributionGroupRepository->removeMembersFromGroup($request, $groupId);
    }

    public function payOut(Request $request, $groupId)
    {
        return $this->contributionGroupRepository->payOut($request, $groupId);
    }
    public function deleteContributionGroup(Request $request, $id)
    {
        return $this->contributionGroupRepository->deleteContributionGroup($request, $id);
    }

}
