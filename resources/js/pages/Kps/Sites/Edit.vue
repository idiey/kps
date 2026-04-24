<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { useLocale } from '@/composables/useLocale';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import type { KpsSite } from '@/types';

const props = defineProps<{
    site: KpsSite;
}>();

const form = useForm({
    name: props.site.name,
    code: props.site.code,
    address: props.site.address || '',
    phone: props.site.phone || '',
    email: props.site.email || '',
    is_active: props.site.is_active,
    hutang_weightage_pct: props.site.hutang_weightage_pct ?? 100,
});

const submit = () => {
    form.put(`/kps/sites/${props.site.id}`);
};

const { t } = useLocale();
</script>

<template>
    <Head :title="t('kps.sites.edit', 'Edit Site')" />

    <KpsShellLayout>
        <div class="space-y-6 max-w-3xl">
            <div>
                <h1 class="text-2xl font-semibold">{{ t('kps.sites.edit', 'Edit Site') }}</h1>
                <p class="text-muted-foreground">{{ t('kps.sites.edit_desc', 'Update site information.') }}</p>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="name">{{ t('auth.name', 'Name') }}</Label>
                    <Input id="name" v-model="form.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="code">{{ t('kps.code', 'Code') }}</Label>
                    <Input id="code" v-model="form.code" />
                </div>
                <div class="grid gap-2">
                    <Label for="address">{{ t('kps.address', 'Address') }}</Label>
                    <Textarea id="address" v-model="form.address" />
                </div>
                <div class="grid gap-2">
                    <Label for="phone">{{ t('kps.phone', 'Phone') }}</Label>
                    <Input id="phone" v-model="form.phone" />
                </div>
                <div class="grid gap-2">
                    <Label for="email">{{ t('auth.email', 'Email') }}</Label>
                    <Input id="email" v-model="form.email" type="email" />
                </div>
                <div class="grid gap-2">
                    <Label for="hutang_weightage_pct">{{ t('kps.hutang_weightage', 'Hutang Weightage (%)') }}</Label>
                    <Input id="hutang_weightage_pct" v-model="form.hutang_weightage_pct" type="number" min="0" max="100" step="0.01" />
                    <p class="text-xs text-muted-foreground">{{ t('kps.hutang_weightage_desc', 'Maximum percentage of a potongan amount that can be auto-allocated to hutang.') }}</p>
                    <p v-if="form.errors.hutang_weightage_pct" class="text-sm text-red-600">{{ form.errors.hutang_weightage_pct }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <Checkbox id="is_active" v-model:checked="form.is_active" />
                    <Label for="is_active">{{ t('admin.active', 'Active') }}</Label>
                </div>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="form.processing">{{ t('common.update', 'Update') }}</Button>
                    <Button variant="outline" as-child>
                        <Link href="/kps/sites">{{ t('common.cancel', 'Cancel') }}</Link>
                    </Button>
                </div>
            </form>
        </div>
    </KpsShellLayout>
</template>
