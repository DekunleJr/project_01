<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { admin } from '@/routes'; // Assuming we add this
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import api from '@/lib/api';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin',
    },
];

interface Group {
    id: number;
    name: string;
    // Add other fields as per model
}

const groups = ref<Group[]>([]);
const newGroupName = ref('');
const selectedGroup = ref<Group | null>(null);
const memberEmail = ref('');

onMounted(async () => {
    await fetchGroups();
});

const fetchGroups = async () => {
    try {
        const response = await api.get('/groups');
        groups.value = response.data;
    } catch (error) {
        console.error('Failed to fetch groups', error);
    }
};

const createGroup = async () => {
    try {
        await api.post('/groups', { name: newGroupName.value });
        newGroupName.value = '';
        await fetchGroups();
    } catch (error) {
        console.error('Failed to create group', error);
    }
};

const deleteGroup = async (id: number) => {
    try {
        await api.delete(`/group/${id}`);
        await fetchGroups();
    } catch (error) {
        console.error('Failed to delete group', error);
    }
};

const assignMember = async (groupId: number) => {
    try {
        await api.post(`/assign-members/${groupId}`, { email: memberEmail.value });
        memberEmail.value = '';
    } catch (error) {
        console.error('Failed to assign member', error);
    }
};

const removeMember = async (groupId: number) => {
    try {
        await api.post(`/remove-members/${groupId}`, { email: memberEmail.value });
        memberEmail.value = '';
    } catch (error) {
        console.error('Failed to remove member', error);
    }
};

const payout = async (groupId: number) => {
    try {
        await api.post(`/payout/${groupId}`);
    } catch (error) {
        console.error('Failed to payout', error);
    }
};
</script>

<template>
    <Head title="Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h1 class="text-2xl mb-4">Admin Panel</h1>

            <!-- Create Group -->
            <div class="mb-6">
                <h2 class="text-xl mb-2">Create Contribution Group</h2>
                <Label for="groupName">Group Name</Label>
                <Input id="groupName" v-model="newGroupName" />
                <Button @click="createGroup" class="mt-2">Create</Button>
            </div>

            <!-- List Groups -->
            <div class="mb-6">
                <h2 class="text-xl mb-2">Contribution Groups</h2>
                <ul>
                    <li v-for="group in groups" :key="group.id" class="mb-4 p-4 border">
                        <h3>{{ group.name }}</h3>
                        <Button @click="deleteGroup(group.id)" variant="destructive">Delete</Button>
                        <Button @click="payout(group.id)" class="ml-2">Payout</Button>

                        <!-- Assign/Remove Members -->
                        <div class="mt-2">
                            <Label for="memberEmail">Member Email</Label>
                            <Input id="memberEmail" v-model="memberEmail" />
                            <Button @click="assignMember(group.id)" class="mt-1">Assign</Button>
                            <Button @click="removeMember(group.id)" variant="outline" class="mt-1 ml-2">Remove</Button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>