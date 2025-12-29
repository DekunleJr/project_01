<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import api from '@/lib/api';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const balance = ref(0);
const paymentHistory = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const balanceResponse = await api.get('/check-balance');
        balance.value = balanceResponse.data.balance;

        const historyResponse = await api.get('/payment-history');
        paymentHistory.value = historyResponse.data.payment;
    } catch (error: any) {
        console.error('Failed to fetch dashboard data', error);

        if (error.response?.status === 401) {
            localStorage.removeItem('api_token');
            router.visit('/login');
        }
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <Head title="Dashboard - ContribManager" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div v-if="loading" class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 p-6 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-300">Loading your dashboard...</p>
            </div>
        </div>
        <div v-else class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 p-6">

            <!-- Welcome -->
            <section class="container mx-auto mb-8">
                <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
                    Welcome back!
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300">
                    Manage your contributions and track your progress.
                </p>
            </section>

            <!-- Stats -->
            <section class="container mx-auto mb-8">
                <div class="grid md:grid-cols-3 gap-6">

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                        <h3 class="text-2xl font-bold">${{ balance }}</h3>
                        <p class="text-gray-500">Current Balance</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                        <h3 class="text-2xl font-bold">{{ paymentHistory.length }}</h3>
                        <p class="text-gray-500">Total Transactions</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                        <h3 class="text-2xl font-bold">3</h3>
                        <p class="text-gray-500">Active Groups</p>
                    </div>

                </div>
            </section>

            <!-- Recent Payments -->
            <section class="container mx-auto">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-4">Recent Payments</h2>

                    <p v-if="paymentHistory.length === 0" class="text-gray-500">
                        No payments yet.
                    </p>

                    <ul v-else class="space-y-3">
                        <li
                            v-for="payment in paymentHistory.slice(0, 5)"
                            :key="payment.id"
                            class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
                        >
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-white">{{ payment.type }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ new Date(payment.created_at).toLocaleDateString() }}</p>
                            </div>
                            <span class="font-bold text-lg" :class="payment.type === 'deposit' ? 'text-green-600' : 'text-red-600'">
                                {{ payment.type === 'deposit' ? '+' : '-' }}${{ payment.amount }}
                            </span>
                        </li>
                    </ul>
                </div>
            </section>

        </div>
    </AppLayout>
</template>
