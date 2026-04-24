<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useLocale } from '@/composables/useLocale';

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

const page = usePage();
const currentMonth = new Date().toISOString().slice(0, 7);
const canManageSiteData = computed(() =>
    (page.props.auth?.permissions ?? []).includes('kps.manage_sites'),
);

const form = useForm({
    site_id: props.site.id,
    month: currentMonth,
    entries: [
        {
            peneroka_id: '',
            amount: '',
        },
    ],
});

const importForm = useForm({
    month: currentMonth,
    file: null as File | null,
});

const entryTotal = computed(() =>
    form.entries.reduce((sum, entry) => sum + Number(entry.amount || 0), 0),
);

const templateDownloadUrl = computed(
    () =>
        `/kps/sites/${props.site.id}/potongan/bulk/template?month=${encodeURIComponent(importForm.month || currentMonth)}`,
);

const addRow = () => {
    form.entries.push({ peneroka_id: '', amount: '' });
};

const removeRow = (index: number) => {
    if (form.entries.length <= 1) return;
    form.entries.splice(index, 1);
};

const submit = () => {
    form.post(`/kps/sites/${props.site.id}/potongan/bulk`);
};

const onImportFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    importForm.file = target.files?.[0] ?? null;
};

const submitImport = () => {
    importForm.post(`/kps/sites/${props.site.id}/potongan/bulk/upload`, {
        forceFormData: true,
        preserveScroll: true,
    });
};

const { t } = useLocale();
</script>

<template>
    <Head :title="t('kps.potongan.bulk', 'Bulk Potongan')" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Deduction Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        {{ t('kps.potongan.bulk', 'Bulk Potongan') }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Enter multiple deductions for {{ site.name }} in one pass. Each row will be allocated using the same live workflow as single-entry potongan.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="`/kps/sites/${site.id}/potongan`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        {{ t('kps.potongan.back_ledger', 'Back to Ledger') }}
                    </Link>
                    <Link
                        :href="`/kps/sites/${site.id}/potongan/create`"
                        class="inline-flex items-center rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] transition hover:translate-y-[-1px]"
                    >
                        {{ t('kps.potongan.single_entry', 'Single Entry') }}
                    </Link>
                </div>
            </section>

            <section
                v-if="canManageSiteData"
                class="rounded-[34px] border border-[#efdcd5] bg-white/90 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]"
            >
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Admin Excel Import</p>
                        <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">
                            Whole-site bulk upload
                        </h2>
                        <p class="mt-3 text-sm leading-7 text-[#65534d]">
                            Download the template with current site data, update it in Excel, then upload to sync peneroka and current month dividend.
                        </p>
                    </div>

                    <a
                        :href="templateDownloadUrl"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        Download Template (.xlsx)
                    </a>
                </div>

                <form class="mt-6 grid gap-4 md:grid-cols-[0.35fr,1fr,auto] md:items-end" @submit.prevent="submitImport">
                    <div class="grid gap-2">
                        <Label for="import-month" class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Month</Label>
                        <Input id="import-month" type="month" v-model="importForm.month" class="h-12 rounded-full border-[#ead6ce] bg-[#fffaf7]" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="import-file" class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Excel File</Label>
                        <input
                            id="import-file"
                            type="file"
                            accept=".xlsx,.xls"
                            class="h-12 rounded-full border border-[#ead6ce] bg-white px-4 text-sm text-[#1b1b1b] file:mr-4 file:rounded-full file:border-0 file:bg-[#171717] file:px-4 file:py-2 file:text-xs file:font-semibold file:text-white"
                            @change="onImportFileChange"
                        >
                    </div>

                    <Button
                        type="submit"
                        :disabled="importForm.processing || !importForm.file"
                        class="h-12 rounded-full bg-[#171717] px-6 text-white hover:bg-[#0f0f0f]"
                    >
                        {{ t('kps.potongan.upload_excel', 'Upload Excel') }}
                    </Button>
                </form>

                <p v-if="importForm.errors.month" class="mt-3 text-sm text-[#b64a2b]">
                    {{ importForm.errors.month }}
                </p>
                <p v-if="importForm.errors.file" class="mt-2 text-sm text-[#b64a2b]">
                    {{ importForm.errors.file }}
                </p>
            </section>

            <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
                <form class="rounded-[34px] border border-[#efdcd5] bg-white/92 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]" @submit.prevent="submit">
                    <div class="flex items-center justify-between gap-4 border-b border-[#f0dfd8] pb-5">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Batch Entry</p>
                            <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Monthly deduction batch</h2>
                        </div>
                        <span class="rounded-full bg-[#171717] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-white">
                            {{ site.code }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-6">
                        <div class="grid gap-2 max-w-sm">
                            <Label for="month" class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Month</Label>
                            <Input id="month" type="month" v-model="form.month" class="h-12 rounded-full border-[#ead6ce] bg-[#fffaf7]" />
                        </div>

                        <div class="space-y-4">
                            <div
                                v-for="(entry, index) in form.entries"
                                :key="index"
                                class="rounded-[28px] border border-[#ead6ce] bg-[#fffaf7] p-5 shadow-[0_10px_26px_rgba(157,80,53,0.05)]"
                            >
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#b47b67]">Row {{ index + 1 }}</p>
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        class="rounded-full text-[#b64a2b] hover:bg-[#fff1ec]"
                                        @click="removeRow(index)"
                                    >
                                        Remove
                                    </Button>
                                </div>

                                <div class="mt-4 grid gap-4 md:grid-cols-[1.5fr,0.6fr]">
                                    <div class="grid gap-2">
                                        <Label class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Peneroka</Label>
                                        <Select v-model="entry.peneroka_id">
                                            <SelectTrigger class="h-12 rounded-full border-[#ead6ce] bg-white">
                                                <SelectValue placeholder="Select peneroka" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="p in penerokas" :key="p.id" :value="p.id">
                                                    {{ p.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <div class="grid gap-2">
                                        <Label class="text-[11px] font-bold uppercase tracking-[0.24em] text-[#9b7d73]">Amount</Label>
                                        <Input type="number" v-model="entry.amount" min="0" step="0.01" class="h-12 rounded-full border-[#ead6ce] bg-white" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            <Button type="button" variant="outline" class="rounded-full border-[#e2c9c0]" @click="addRow">
                                {{ t('kps.potongan.add_row', 'Add Row') }}
                            </Button>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-full bg-[#171717] px-6 text-white hover:bg-[#0f0f0f]"
                            >
                                {{ t('kps.potongan.save_bulk', 'Save Bulk') }}
                            </Button>
                        </div>
                    </div>
                </form>

                <aside class="rounded-[34px] border border-[#efdcd5] bg-white/88 p-7 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#b47b67]">Batch Summary</p>
                    <h2 class="mt-2 text-2xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">Operational notes</h2>

                    <div class="mt-6 grid gap-4">
                        <div class="rounded-[26px] bg-[#fff5eb] px-5 py-4">
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#b7654b]">Rows</p>
                            <p class="mt-1 text-2xl font-black text-[#1b1b1b]">{{ form.entries.length }}</p>
                        </div>
                        <div class="rounded-[26px] bg-[#fff5eb] px-5 py-4">
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#b7654b]">Planned Total</p>
                            <p class="mt-1 text-2xl font-black text-[#1b1b1b]">{{ entryTotal.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4 text-sm leading-7 text-[#65534d]">
                        <p>1. Select the month once for the full batch.</p>
                        <p>2. Duplicate peneroka rows are merged by the backend update-or-create workflow.</p>
                        <p>3. Every saved deduction is pushed through the same allocation engine as single entry.</p>
                    </div>
                </aside>
            </section>
        </div>
    </KpsShellLayout>
</template>
