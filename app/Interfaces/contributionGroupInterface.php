<?php

namespace App\Interfaces;

interface contributionGroupInterface
{
    //
    public function getAllContributionGroups();

    public function getContributionGroupById($id);

    public function createContributionGroup(array $data);

    public function updateContributionGroup($id, array $data);

    public function deleteContributionGroup($id);
}
