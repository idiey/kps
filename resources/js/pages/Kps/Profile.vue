<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { useLocale } from '@/composables/useLocale';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import KpsShellLayout from '@/layouts/kps/KpsShellLayout.vue';
import { send } from '@/routes/verification';
import type { AppPageProps } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

const props = defineProps<Props>();
const page = usePage<AppPageProps>();
const user = computed(() => page.props.auth.user);

const form = useForm({
    name: user.value.name,
    email: user.value.email,
});

const submit = () => {
    form.patch('/kps/profile', {
        preserveScroll: true,
    });
};

const { t } = useLocale();
</script>

<template>
    <Head :title="t('settings.profile_title', 'Profile settings')" />

    <KpsShellLayout>
        <div class="mx-auto max-w-3xl space-y-6 pt-2">
            <section class="rounded-[28px] border border-[#f0dbd4] bg-white/90 p-6 shadow-[0_18px_50px_rgba(157,80,53,0.08)]">
                <div class="mb-6">
                    <h1 class="text-xl font-black text-[#1b1b1b]">{{ t('settings.profile_title', 'Profile settings') }}</h1>
                    <p class="mt-1 text-sm text-[#65534d]">
                        {{ t('kps.profile.desc', 'Update your account information inside KPS workspace.') }}
                    </p>
                </div>

                <form class="space-y-5" @submit.prevent="submit">
                    <div class="grid gap-2">
                        <Label for="name" class="text-[#2d241f]">{{ t('auth.name', 'Name') }}</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            required
                            autocomplete="name"
                            class="border-[#ead6ce] bg-white text-[#2d241f]"
                            :placeholder="t('auth.full_name', 'Full name')"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email" class="text-[#2d241f]">{{ t('auth.email', 'Email address') }}</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="username"
                            class="border-[#ead6ce] bg-white text-[#2d241f]"
                            :placeholder="t('auth.email', 'Email address')"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div v-if="props.mustVerifyEmail && !user.email_verified_at" class="rounded-xl border border-[#f5d9ce] bg-[#fff6f1] px-4 py-3">
                        <p class="text-sm text-[#7c4d3e]">
                            {{ t('settings.email_unverified', 'Your email address is unverified.') }}
                            <Link
                                :href="send()"
                                method="post"
                                as="button"
                                class="font-semibold underline underline-offset-2"
                            >
                                {{ t('settings.resend_verification', 'Resend verification email') }}
                            </Link>
                        </p>
                        <p
                            v-if="props.status === 'verification-link-sent'"
                            class="mt-2 text-sm font-semibold text-[#1f7a45]"
                        >
                            {{ t('auth.verify.sent', 'A new verification link has been sent.') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <Button
                            type="submit"
                            class="bg-[#171717] text-white hover:bg-black"
                            :disabled="form.processing"
                            data-test="update-profile-button"
                        >
                            {{ t('common.save', 'Save') }}
                        </Button>
                        <p v-if="form.recentlySuccessful" class="text-sm font-medium text-[#1f7a45]">
                            {{ t('common.saved', 'Saved.') }}
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </KpsShellLayout>
</template>
