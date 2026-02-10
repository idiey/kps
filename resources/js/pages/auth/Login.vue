<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(store.url(), {
        onFinish: () => form.reset('password'),
    });
};

const autoLogin = (email: string, role: string) => {
    form.email = email;
    form.password = 'password';
    submit();
};
</script>

<template>
    <AuthBase
        title="Log in to your account"
        description="Enter your email and password below to log in"
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm"
                            :tabindex="5"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox 
                            id="remember" 
                            name="remember" 
                            v-model:checked="form.remember" 
                            :tabindex="3" 
                        />
                        <span>Remember me</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full"
                    :tabindex="4"
                    :disabled="form.processing"
                    data-test="login-button"
                >
                    <Spinner v-if="form.processing" />
                    Log in
                </Button>
            </div>

            <div
                class="text-center text-sm text-muted-foreground"
                v-if="canRegister"
            >
                Don't have an account?
                <TextLink :href="register()" :tabindex="5">Sign up</TextLink>
            </div>
        </form>

        <!-- Seeded Accounts (Development Only) -->
        <div class="mt-6 rounded-lg border border-dashed border-muted-foreground/30 bg-muted/30 p-4">
            <details class="group" open>
                <summary class="cursor-pointer text-sm font-medium text-muted-foreground hover:text-foreground">
                    🧪 Seeded Accounts (Click to login)
                </summary>
                <div class="mt-4 space-y-3 text-xs">
                    <div 
                        @click="autoLogin('global-admin@felda.gov.my', 'Global Admin')"
                        class="rounded-md border bg-background p-3 cursor-pointer hover:bg-muted/50 transition-colors group/item"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold group-hover/item:text-primary transition-colors">Global Admin</span>
                            <span class="rounded-full bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 text-amber-700 dark:text-amber-300">Pentadbiran</span>
                        </div>
                        <div class="space-y-1 text-muted-foreground">
                            <div><span class="font-medium">Email:</span> global-admin@felda.gov.my</div>
                            <div><span class="font-medium">Password:</span> password</div>
                            <div class="text-[10px] mt-2 text-muted-foreground/70">Full system access, manage everything</div>
                        </div>
                    </div>

                    <div 
                        @click="autoLogin('hq-admin@felda.gov.my', 'HQ Admin')"
                        class="rounded-md border bg-background p-3 cursor-pointer hover:bg-muted/50 transition-colors group/item"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold group-hover/item:text-primary transition-colors">Ahmad HQ Admin</span>
                            <span class="rounded-full bg-indigo-100 dark:bg-indigo-900/30 px-2 py-0.5 text-indigo-700 dark:text-indigo-300">Company Admin</span>
                        </div>
                        <div class="space-y-1 text-muted-foreground">
                            <div><span class="font-medium">Email:</span> hq-admin@felda.gov.my</div>
                            <div><span class="font-medium">Password:</span> password</div>
                            <div class="text-[10px] mt-2 text-muted-foreground/70">Manage all sites, users, reports, and KPS settings</div>
                        </div>
                    </div>

                    <div 
                        @click="autoLogin('admin-st@felda.gov.my', 'Site Admin ST')"
                        class="rounded-md border bg-background p-3 cursor-pointer hover:bg-muted/50 transition-colors group/item"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold group-hover/item:text-primary transition-colors">Fatimah Site Admin</span>
                            <span class="rounded-full bg-cyan-100 dark:bg-cyan-900/30 px-2 py-0.5 text-cyan-700 dark:text-cyan-300">Penyelia + Site Admin</span>
                        </div>
                        <div class="space-y-1 text-muted-foreground">
                            <div><span class="font-medium">Email:</span> admin-st@felda.gov.my</div>
                            <div><span class="font-medium">Password:</span> password</div>
                            <div class="text-[10px] mt-2 text-muted-foreground/70">Site admin for FELDA Sungai Tekam — manage peneroka, hutang, potongan</div>
                        </div>
                    </div>

                    <div 
                        @click="autoLogin('admin-jk@felda.gov.my', 'Site Admin JK')"
                        class="rounded-md border bg-background p-3 cursor-pointer hover:bg-muted/50 transition-colors group/item"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold group-hover/item:text-primary transition-colors">Razak Site Admin</span>
                            <span class="rounded-full bg-cyan-100 dark:bg-cyan-900/30 px-2 py-0.5 text-cyan-700 dark:text-cyan-300">Penyelia + Site Admin</span>
                        </div>
                        <div class="space-y-1 text-muted-foreground">
                            <div><span class="font-medium">Email:</span> admin-jk@felda.gov.my</div>
                            <div><span class="font-medium">Password:</span> password</div>
                            <div class="text-[10px] mt-2 text-muted-foreground/70">Site admin for FELDA Jengka — manage peneroka, hutang, potongan</div>
                        </div>
                    </div>

                    <div 
                        @click="autoLogin('staff-st@felda.gov.my', 'Site Staff')"
                        class="rounded-md border bg-background p-3 cursor-pointer hover:bg-muted/50 transition-colors group/item"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold group-hover/item:text-primary transition-colors">Siti Staff</span>
                            <span class="rounded-full bg-pink-100 dark:bg-pink-900/30 px-2 py-0.5 text-pink-700 dark:text-pink-300">Kaunter</span>
                        </div>
                        <div class="space-y-1 text-muted-foreground">
                            <div><span class="font-medium">Email:</span> staff-st@felda.gov.my</div>
                            <div><span class="font-medium">Password:</span> password</div>
                            <div class="text-[10px] mt-2 text-muted-foreground/70">Front desk staff for FELDA Sungai Tekam — data entry, view KPS</div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t text-[10px] text-muted-foreground/70">
                        💡 Click on any account card above to auto-login
                    </div>
                </div>
            </details>
        </div>
    </AuthBase>
</template>
