<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { LogOut, Settings } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    user: User;
    settingsHref?: string;
}

const handleLogout = () => {
    router.flushAll();
};

const props = defineProps<Props>();
const settingsHref = computed(() => props.settingsHref ?? edit().url);
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal text-[#2d241f]">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm text-[#2d241f]">
            <UserInfo :user="props.user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator class="bg-[#ebdbd4]" />
    <DropdownMenuGroup>
        <DropdownMenuItem
            :as-child="true"
            class="text-[#2d241f] focus:bg-[#fff1ec] focus:text-[#2d241f]"
        >
            <Link class="block w-full text-left text-[#2d241f]" :href="settingsHref" prefetch as="button">
                <Settings class="mr-2 h-4 w-4" />
                Settings
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator class="bg-[#ebdbd4]" />
    <DropdownMenuItem
        :as-child="true"
        class="text-[#2d241f] focus:bg-[#fff1ec] focus:text-[#2d241f]"
    >
        <Link
            class="block w-full text-left text-[#2d241f]"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>
