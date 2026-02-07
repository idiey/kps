<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { digitWorkshop } from '@/styles/digit-workshop-ui';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import {
    Activity,
    BarChart3,
    CheckCircle,
    Cpu,
    Send,
    TrendingUp,
    Users,
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-8">
            <header class="space-y-2">
                <h1 :class="digitWorkshop.typography.pageTitle">
                    Welcome Back, Team!
                </h1>
                <p :class="digitWorkshop.typography.pageSubtitle">
                    Ready to boost your sales? Send WhatsApp blast campaigns,
                    manage your leads, and scale your messaging reach.
                </p>
            </header>

            <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
                <section
                    :class="[digitWorkshop.card.cardGradientHero, digitWorkshop.spacing.card]"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div :class="digitWorkshop.card.iconChip">
                                <TrendingUp class="size-5" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-base font-semibold text-[#303438]">
                                        Usage Analytics
                                    </h2>
                                    <span class="rounded-full border border-[#CFE7D6] bg-white/80 px-2 py-0.5 text-[10px] font-semibold text-[#2E6B35]">
                                        PRO
                                    </span>
                                </div>
                                <p class="text-xs text-[#6B6B6B]">
                                    Snapshot of today and this month
                                </p>
                            </div>
                        </div>
                        <button class="text-xs font-medium text-[#6B6B6B]">
                            View Campaigns ->
                        </button>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <div class="text-xs text-[#6B6B6B]">Today</div>
                            <div class="text-2xl font-semibold">2</div>
                            <div class="h-2 w-full rounded-full bg-white/80">
                                <div class="h-2 w-1/4 rounded-full bg-[#84C024]"></div>
                            </div>
                            <div class="text-[11px] text-[#8A8A8A]">3.5K left</div>
                        </div>
                        <div class="space-y-2">
                            <div class="text-xs text-[#6B6B6B]">This Month</div>
                            <div class="text-2xl font-semibold">35</div>
                            <div class="h-2 w-full rounded-full bg-white/80">
                                <div class="h-2 w-1/2 rounded-full bg-[#84C024]"></div>
                            </div>
                            <div class="text-[11px] text-[#8A8A8A]">100K left</div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 sm:grid-cols-3">
                        <div>
                            <div class="text-xs text-[#6B6B6B]">Campaigns left</div>
                            <div class="text-lg font-semibold text-[#84C024]">25</div>
                        </div>
                        <div>
                            <div class="text-xs text-[#6B6B6B]">Templates</div>
                            <div class="text-lg font-semibold text-[#84C024]">0 / 8</div>
                        </div>
                        <div>
                            <div class="text-xs text-[#6B6B6B]">Devices</div>
                            <div class="text-lg font-semibold text-[#84C024]">1 / 3</div>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col gap-6">
                    <section
                        :class="[digitWorkshop.card.cardGradientMint, digitWorkshop.spacing.card]"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div :class="digitWorkshop.card.iconChipSoft">
                                    <Users class="size-5" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-[#303438]">
                                        Manage Leads
                                    </h3>
                                    <p class="text-xs text-[#6B6B6B]">Organize & convert</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-xs text-[#6B6B6B]">Total Leads</div>
                                <div class="text-lg font-semibold text-[#2E6B35]">1416</div>
                            </div>
                            <div>
                                <div class="text-xs text-[#6B6B6B]">Lead Groups</div>
                                <div class="text-lg font-semibold text-[#2E6B35]">2</div>
                            </div>
                        </div>
                        <button class="mt-6 text-xs font-medium text-[#2E6B35]">
                            View leads ->
                        </button>
                    </section>

                    <section
                        :class="[digitWorkshop.card.cardGradientMint, digitWorkshop.spacing.card]"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div :class="digitWorkshop.card.iconChipSoft">
                                    <Cpu class="size-5" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-[#303438]">
                                        Device Status
                                    </h3>
                                    <p class="text-xs text-[#6B6B6B]">Monitor & manage</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-white/70 px-2 py-1 text-[10px] font-semibold text-[#2E6B35]">Online</span>
                        </div>
                        <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-xs text-[#6B6B6B]">Connected</div>
                                <div class="text-lg font-semibold text-[#2E6B35]">1</div>
                            </div>
                            <div>
                                <div class="text-xs text-[#6B6B6B]">Status</div>
                                <div class="text-lg font-semibold text-[#2E6B35]">Active</div>
                            </div>
                        </div>
                        <button class="mt-6 text-xs font-medium text-[#2E6B35]">
                            Check devices ->
                        </button>
                    </section>
                </div>
            </div>

            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 :class="digitWorkshop.typography.sectionTitle">
                        Performance Overview
                    </h2>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div :class="digitWorkshop.card.statCard">
                        <div :class="digitWorkshop.card.iconChipSoft + ' size-9'">
                            <Activity class="size-4" />
                        </div>
                        <div class="mt-3 text-2xl font-semibold">19</div>
                        <div class="text-xs text-[#6B6B6B]">Total Campaigns</div>
                    </div>
                    <div :class="digitWorkshop.card.statCard">
                        <div :class="digitWorkshop.card.iconChipBlue + ' size-9'">
                            <Send class="size-4" />
                        </div>
                        <div class="mt-3 text-2xl font-semibold">35</div>
                        <div class="text-xs text-[#6B6B6B]">Messages Sent</div>
                    </div>
                    <div :class="digitWorkshop.card.statCard">
                        <div :class="digitWorkshop.card.iconChip + ' size-9'">
                            <CheckCircle class="size-4" />
                        </div>
                        <div class="mt-3 text-2xl font-semibold">35</div>
                        <div class="text-xs text-[#6B6B6B]">Delivered</div>
                    </div>
                    <div :class="digitWorkshop.card.statCard">
                        <div :class="digitWorkshop.card.iconChipBlue + ' size-9'">
                            <BarChart3 class="size-4" />
                        </div>
                        <div class="mt-3 text-2xl font-semibold">100%</div>
                        <div class="text-xs text-[#6B6B6B]">Delivery Rate</div>
                    </div>
                    <div :class="digitWorkshop.card.statCard">
                        <div :class="digitWorkshop.card.iconChipYellow + ' size-9'">
                            <TrendingUp class="size-4" />
                        </div>
                        <div class="mt-3 text-2xl font-semibold">100.0%</div>
                        <div class="text-xs text-[#6B6B6B]">Open Rate</div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
