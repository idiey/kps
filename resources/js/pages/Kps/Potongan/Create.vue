<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import type { KpsSite, KpsSiteRole, KpsPeneroka } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    penerokas: KpsPeneroka[];
}>();

const form = useForm({
    site_id: props.site.id,
    peneroka_id: '',
    month: '',
    amount: '',
});

const submit = () => {
    form.post(`/kps/sites/${props.site.id}/potongan`);
};

const errorFor = (field: keyof typeof form.errors) => form.errors[field];
</script>

<template>
    <Head title="Create Potongan" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Deduction Entry
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        Create Potongan
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Add a monthly deduction for {{ site.name }}. The deduction will immediately enter the allocation workflow after saving.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="`/kps/sites/${site.id}/potongan/bulk`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Bulk Entry
                    </Link>
                    <Link
                        :href="`/kps/sites/${site.id}/potongan`"
                        class="inline-flex items-center rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] transition hover:translate-y-[-1px]"
                    >
                        Back to Ledger
                    </Link>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
                <form class="rounded-[34px] border border-[#efdcd5] bg-white/92 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]" @submit.prevent="submit">
                    <div class="flex items-center justify-between gap-4 border-b border-[#f0dfd8] pb-5">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Single Entry</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Deduction details</h2>
                        </div>
                        <span class="rounded-full bg-[#171717] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-white">
                            {{ site.code }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-5">
                        <div class="grid gap-2">
                            <Label class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka</Label>
                            <Select v-model="form.peneroka_id">
                                <SelectTrigger class="h-12 rounded-full border-[#ead6ce] bg-[#fffaf7]">
                                    <SelectValue placeholder="Select peneroka" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="p in penerokas" :key="p.id" :value="p.id">
                                        {{ p.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="errorFor('peneroka_id')" class="text-sm text-red-600">{{ errorFor('peneroka_id') }}</p>
                        </div>

                        <div class="grid gap-2 md:grid-cols-2">
                            <div class="grid gap-2">
                                <Label for="month" class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Month</Label>
                                <Input id="month" type="month" v-model="form.month" class="h-12 rounded-full border-[#ead6ce] bg-[#fffaf7]" />
                                <p v-if="errorFor('month')" class="text-sm text-red-600">{{ errorFor('month') }}</p>
                            </div>
                            <div class="grid gap-2">
                                <Label for="amount" class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Amount</Label>
                                <Input id="amount" type="number" v-model="form.amount" min="0" step="0.01" class="h-12 rounded-full border-[#ead6ce] bg-[#fffaf7]" />
                                <p v-if="errorFor('amount')" class="text-sm text-red-600">{{ errorFor('amount') }}</p>
                            </div>
                        </div>

                        <div class="rounded-[26px] bg-[#fff5eb] px-5 py-4 text-sm leading-6 text-[#6d5952]">
                            The saved deduction will be allocated immediately using the current debt priority waterfall.
                        </div>

                        <div class="flex flex-wrap gap-3 pt-2">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-full bg-[#171717] px-6 text-white hover:bg-[#0f0f0f]"
                            >
                                Save Potongan
                            </Button>
                            <Button variant="outline" as-child class="rounded-full border-[#e2c9c0]">
                                <Link :href="`/kps/sites/${site.id}/potongan`">Cancel</Link>
                            </Button>
                        </div>
                    </div>
                </form>

                <aside class="rounded-[34px] border border-[#efdcd5] bg-white/88 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Operational Notes</p>
                    <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">What happens next</h2>
                    <div class="mt-6 space-y-4 text-sm leading-7 text-[#65534d]">
                        <p>1. The deduction is saved for the selected site and month.</p>
                        <p>2. Allocation service distributes the amount by debt priority.</p>
                        <p>3. Any unallocated amount stays visible in the live allocation review.</p>
                    </div>
                </aside>
            </section>
        </div>
    </KpsShellLayout>
</template>
