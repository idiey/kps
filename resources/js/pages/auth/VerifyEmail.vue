<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { useLocale } from '@/composables/useLocale';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';
import { send } from '@/routes/verification';
import { Form, Head } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const { t } = useLocale();
</script>

<template>
    <AuthLayout
        :title="t('auth.verify.title', 'Verify email')"
        :description="t('auth.verify.description', 'Please verify your email address by clicking on the link we just emailed to you.')"
    >
        <Head :title="t('auth.verify.head', 'Email verification')" />

        <div
            v-if="status === 'verification-link-sent'"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ t('auth.verify.sent', 'A new verification link has been sent to the email address you provided during registration.') }}
        </div>

        <Form
            v-bind="send.form()"
            class="space-y-6 text-center"
            v-slot="{ processing }"
        >
            <Button :disabled="processing" variant="secondary">
                <Spinner v-if="processing" />
                {{ t('auth.verify.resend', 'Resend verification email') }}
            </Button>

            <TextLink
                :href="logout()"
                as="button"
                class="mx-auto block text-sm"
            >
                {{ t('menu.logout', 'Log out') }}
            </TextLink>
        </Form>
    </AuthLayout>
</template>
