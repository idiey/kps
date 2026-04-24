<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useLocale } from '@/composables/useLocale';

import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import type { KpsPeneroka, KpsSite, KpsSiteRole } from '@/types';

const props = defineProps<{
    site: KpsSite;
    siteRole?: KpsSiteRole | null;
    peneroka: KpsPeneroka;
}>();

const form = useForm({
    site_id: props.site.id,
    name: props.peneroka.name,
    ic_number: props.peneroka.ic_number || '',
    phone: props.peneroka.phone || '',
    address: props.peneroka.address || '',
});

const submit = () => {
    form.put(`/kps/sites/${props.site.id}/peneroka/${props.peneroka.id}`);
};

const { t } = useLocale();
</script>

<template>
    <Head :title="`${t('kps.peneroka.edit', 'Edit Peneroka')} - ${peneroka.name}`" />

    <KpsShellLayout :site="site" :site-role="siteRole">
        <div class="space-y-6 px-4 pb-8 lg:px-8">
            <section class="flex flex-col gap-5 2xl:flex-row 2xl:items-end 2xl:justify-between">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.32em] text-[#b7654b]">
                        Site Workspace
                    </p>
                    <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] text-[#1b1b1b] md:text-5xl" style="font-family: Manrope, Inter, sans-serif;">
                        {{ t('kps.peneroka.edit', 'Edit peneroka') }}
                    </h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-[#65534d] md:text-lg">
                        Update the record for {{ peneroka.name }} while preserving the live site workflow.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="`/kps/sites/${site.id}/peneroka`"
                        class="inline-flex items-center rounded-full border border-[#e1cbc2] bg-white px-6 py-3 text-sm font-semibold text-[#6d5952] shadow-[0_10px_30px_rgba(157,80,53,0.06)] transition hover:border-[#c77d62] hover:text-[#1b1b1b]"
                    >
                        {{ t('kps.registry.back', 'Back to Registry') }}
                    </Link>
                </div>
            </section>

            <div class="grid gap-6 lg:grid-cols-[1.4fr,0.9fr]">
                <Card class="rounded-[34px] border-[#efdcd5] bg-white/92 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                    <CardHeader class="border-b border-[#f0dfd8] pb-6">
                        <CardTitle class="text-xl font-black text-[#1b1b1b]" style="font-family: Manrope, Inter, sans-serif;">{{ t('kps.peneroka.details', 'Peneroka details') }}</CardTitle>
                        <p class="text-sm text-[#7f675f]">The site assignment stays fixed while the personal record is updated.</p>
                    </CardHeader>

                    <CardContent class="pt-6">
                        <form class="space-y-5" @submit.prevent="submit">
                            <div class="grid gap-2">
                                <Label for="name" class="text-sm font-semibold text-[#2a2422]">Name</Label>
                                <Input id="name" v-model="form.name" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label for="ic_number" class="text-sm font-semibold text-[#2a2422]">IC Number</Label>
                                    <Input id="ic_number" v-model="form.ic_number" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                    <InputError :message="form.errors.ic_number" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="phone" class="text-sm font-semibold text-[#2a2422]">Phone</Label>
                                    <Input id="phone" v-model="form.phone" class="rounded-2xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                    <InputError :message="form.errors.phone" />
                                </div>
                            </div>

                            <div class="grid gap-2">
                                <Label for="address" class="text-sm font-semibold text-[#2a2422]">Address</Label>
                                <Textarea id="address" v-model="form.address" rows="5" class="rounded-3xl border-[#e6d1c7] bg-[#fffaf8] px-4 py-3" />
                                <InputError :message="form.errors.address" />
                            </div>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-full bg-gradient-to-br from-[#d6522d] to-[#bc3f1d] px-6 py-3 text-white shadow-[0_16px_32px_rgba(214,82,45,0.28)] hover:translate-y-[-1px]"
                                >
                                    {{ t('kps.peneroka.update', 'Update Peneroka') }}
                                </Button>
                                <Button variant="outline" as-child class="rounded-full border-[#e2c9c0] text-[#6d5952] hover:border-[#c77d62] hover:text-[#1b1b1b]">
                                    <Link :href="`/kps/sites/${site.id}/peneroka`">{{ t('common.cancel', 'Cancel') }}</Link>
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
                            <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/55">Peneroka</p>
                            <p class="mt-1 text-lg font-semibold">{{ peneroka.name }}</p>
                            <p class="text-sm text-white/70">{{ peneroka.ic_number || 'No IC number' }}</p>
                        </div>

                        <div class="space-y-3 text-sm text-[#65534d]">
                            <p>Editing this record does not change the site assignment.</p>
                            <p>Keep contact details current so reports and recovery workflows remain accurate.</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </KpsShellLayout>
</template>
