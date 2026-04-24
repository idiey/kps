<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useLocale } from '@/composables/useLocale';
import { Head, Link } from '@inertiajs/vue3';

const { t } = useLocale();

const breadcrumbs = [
    {
        title: t('nav.dashboard', 'Dashboard'),
        href: '/dashboard',
    },
];
</script>

<template>
    <Head :title="t('nav.dashboard', 'Dashboard')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col items-center justify-center space-y-4 py-8">
             <div class="text-center">
                <h1 class="text-xl font-semibold">{{ t('dashboard.system', 'KPS System') }}</h1>
                <p class="text-gray-500">{{ t('dashboard.welcome', 'Welcome to KPS System.') }}</p>
             </div>
             <div>
                <Link href="/kps/dashboard" class="text-blue-600 underline hover:text-blue-800">
                    {{ t('dashboard.go_kps', 'Go to KPS Dashboard') }}
                </Link>
             </div>
        </div>
    </AppLayout>
</template>
