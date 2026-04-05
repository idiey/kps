<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { KpsDebt, KpsSite, KpsSiteRole } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    debt: KpsDebt;
}>();

const form = useForm({
    priority: props.debt.priority,
    balance: props.debt.balance,
    monthly_potongan_limit: props.debt.monthly_potongan_limit ?? '',
    due_date: props.debt.due_date || '',
    description: props.debt.description || '',
});

const submit = () => {
    form.put(`/kps/sites/${props.site.id}/hutang/${props.debt.id}`);
};
</script>

<template>
    <Head :title="`Edit Hutang - ${debt.description || debt.id}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Site Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Edit hutang
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Update the debt record while keeping the live allocation workflow intact.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="`/kps/sites/${site.id}/hutang`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Back to Debts
                    </Link>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-[1.4fr,0.9fr]">
                <Card class="rounded-[34px] border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <CardHeader class="border-b border-[#f0dfd8] pb-6">
                        <CardTitle class="text-xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Debt details</CardTitle>
                        <p class="text-sm text-[#7f675f]">Priority and balance affect recovery order immediately after saving.</p>
                    </CardHeader>

                    <CardContent class="pt-6">
                        <form class="space-y-5" @submit.prevent="submit">
                            <div class="grid gap-2 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="priority" class="text-sm font-semibold text-[#2a2422]">Priority</Label>
                                    <Input id="priority" type="number" v-model="form.priority" min="1" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                    <InputError :message="form.errors.priority" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="balance" class="text-sm font-semibold text-[#2a2422]">Balance</Label>
                                    <Input id="balance" type="number" v-model="form.balance" min="0" step="0.01" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                    <InputError :message="form.errors.balance" />
                                </div>
                            </div>

                            <div class="grid gap-2">
                                <Label for="monthly_potongan_limit" class="text-sm font-semibold text-[#2a2422]">Monthly Potongan Limit</Label>
                                <Input id="monthly_potongan_limit" type="number" v-model="form.monthly_potongan_limit" min="0" step="0.01" placeholder="Leave empty for no limit" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                <InputError :message="form.errors.monthly_potongan_limit" />
                                <p class="text-xs text-[#8d7167]">Maximum amount allocated to this hutang per month before moving to next priority.</p>
                            </div>

                            <div class="grid gap-2 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="due_date" class="text-sm font-semibold text-[#2a2422]">Due Date</Label>
                                    <Input id="due_date" type="date" v-model="form.due_date" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                    <InputError :message="form.errors.due_date" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="description" class="text-sm font-semibold text-[#2a2422]">Description</Label>
                                    <Input id="description" v-model="form.description" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] hover:translate-y-[-1px]"
                                >
                                    Update Hutang
                                </Button>
                                <Button variant="outline" as-child class="rounded-full border-[#e2c9c0] text-[#6d5952] hover:border-[#c77d62] hover:text-[#1b1b1b]">
                                    <Link :href="`/kps/sites/${site.id}/hutang`">Cancel</Link>
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <Card class="rounded-[34px] border-[#efdcd5] bg-white/88 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <CardHeader class="border-b border-[#f0dfd8] pb-6">
                        <CardTitle class="text-xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Current record</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 pt-6">
                        <div class="rounded-[24px] bg-[#171717] px-4 py-4 text-white shadow-[0_16px_30px_rgba(23,23,23,0.2)]">
                            <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/55">Debt</p>
                            <p class="mt-1 text-lg font-semibold">{{ debt.description || debt.id }}</p>
                            <p class="text-sm text-white/70">Priority {{ debt.priority }}</p>
                        </div>

                        <div class="space-y-3 text-sm text-[#65534d]">
                            <p>Reordering priority changes the allocation waterfall for this balance.</p>
                            <p>Keep the description short but specific for statements and reviews.</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </KpsShellLayout>
</template>
