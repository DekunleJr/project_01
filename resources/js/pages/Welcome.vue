<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="ContribManager - Manage Your Contributions Effortlessly">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
        <!-- Header -->
        <header class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">ContribManager</h1>
            <nav class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                    >
                        <Button>Register</Button>
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-6 py-20 text-center">
            <h1 class="text-5xl font-bold text-gray-800 dark:text-white mb-6">
                Manage Contributions & Payouts with Ease
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                ContribManager is your ultimate tool for organizing contribution groups, tracking payments, and ensuring timely payouts. Whether you're managing personal savings or community funds, we've got you covered.
            </p>
            <div class="flex justify-center gap-4">
                <Link v-if="!$page.props.auth.user" :href="register()">
                    <Button size="lg" class="bg-indigo-600 hover:bg-indigo-700 text-white">
                        Get Started
                    </Button>
                </Link>
                <Link v-if="$page.props.auth.user" :href="dashboard()">
                    <Button size="lg" variant="outline">
                        Go to Dashboard
                    </Button>
                </Link>
            </div>
        </section>

        <!-- Features Section -->
        <section class="container mx-auto px-6 py-20">
            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">
                Why Choose ContribManager?
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Group Management</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Easily create and manage contribution groups. Assign members, track contributions, and handle payouts seamlessly.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Secure Payments</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Track deposits, withdrawals, and payments with full transparency. Ensure every transaction is recorded and secure.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                    <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Automated Payouts</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Set up automatic payouts based on your group's rules. No more manual calculations or delays.
                    </p>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="bg-gray-50 dark:bg-gray-700 py-20">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">
                    How It Works
                </h2>
                <div class="grid md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Create a Group</h3>
                        <p class="text-gray-600 dark:text-gray-300">Set up your contribution group with custom rules.</p>
                    </div>
                    <div>
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Invite Members</h3>
                        <p class="text-gray-600 dark:text-gray-300">Add contributors and manage memberships.</p>
                    </div>
                    <div>
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Track Payments</h3>
                        <p class="text-gray-600 dark:text-gray-300">Monitor deposits and contributions in real-time.</p>
                    </div>
                    <div>
                        <div class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Payout</h3>
                        <p class="text-gray-600 dark:text-gray-300">Automatically distribute funds when ready.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 dark:bg-gray-900 text-white py-8">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; 2025 ContribManager. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>
