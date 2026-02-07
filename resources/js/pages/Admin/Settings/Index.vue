<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Settings, Save, RefreshCw } from 'lucide-vue-next';

interface Setting {
    id: number;
    key: string;
    value: string;
    group: string;
    type: string;
    description?: string;
}

const props = defineProps<{
    settings: Record<string, Setting[]>;
}>();

// Group settings into forms
const generalSettings = ref(props.settings.general || []);
const jobSettings = ref(props.settings.jobs || []);
const notificationSettings = ref(props.settings.notifications || []);
const inventorySettings = ref(props.settings.inventory || []);

const form = useForm({
    settings: [] as any[],
});

const prepareSettings = (settings: Setting[]) => {
    return settings.map(s => ({
        key: s.key,
        value: s.type === 'boolean' ? (s.value === 'true' || s.value === '1') : s.value,
        group: s.group,
        type: s.type,
    }));
};

const saveSettings = (groupSettings: Setting[]) => {
    form.settings = prepareSettings(groupSettings);
    form.patch('/admin/settings', {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by flash message
        },
    });
};

const initializeDefaults = () => {
    if (confirm('Initialize default settings? This will create missing default settings but will not overwrite existing ones.')) {
        router.post('/admin/settings/initialize');
    }
};

const getValue = (setting: Setting) => {
    if (setting.type === 'boolean') {
        return setting.value === 'true' || setting.value === '1';
    }
    return setting.value;
};

const updateValue = (setting: Setting, value: any) => {
    setting.value = String(value);
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Settings', href: '/admin/settings' },
];
</script>

<template>
    <Head title="Settings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">System Settings</h2>
                <p class="text-muted-foreground">Configure application-wide settings</p>
            </div>
            <Button variant="outline" @click="initializeDefaults">
                <RefreshCw class="mr-2 h-4 w-4" />
                Initialize Defaults
            </Button>
        </div>

        <Tabs default-value="general" class="w-full">
            <TabsList class="grid w-full grid-cols-4">
                <TabsTrigger value="general">General</TabsTrigger>
                <TabsTrigger value="jobs">Jobs</TabsTrigger>
                <TabsTrigger value="notifications">Notifications</TabsTrigger>
                <TabsTrigger value="inventory">Inventory</TabsTrigger>
            </TabsList>

            <!-- General Settings -->
            <TabsContent value="general">
                <Card>
                    <CardHeader>
                        <CardTitle>General Settings</CardTitle>
                        <CardDescription>Configure general application settings</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div v-for="setting in generalSettings" :key="setting.id" class="space-y-2">
                            <Label :for="setting.key">
                                {{ setting.description || setting.key }}
                            </Label>
                            
                            <Switch
                                v-if="setting.type === 'boolean'"
                                :id="setting.key"
                                :checked="getValue(setting)"
                                @update:checked="(val: boolean) => updateValue(setting, val)"
                            />
                            
                            <Input
                                v-else-if="setting.type === 'integer'"
                                :id="setting.key"
                                type="number"
                                :model-value="setting.value"
                                @update:model-value="(val: string | number) => updateValue(setting, val)"
                            />
                            
                            <Input
                                v-else
                                :id="setting.key"
                                :model-value="setting.value"
                                @update:model-value="(val: string | number) => updateValue(setting, val)"
                            />
                        </div>
                        
                        <Button @click="saveSettings(generalSettings)" :disabled="form.processing">
                            <Save class="mr-2 h-4 w-4" />
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </Button>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Job Settings -->
            <TabsContent value="jobs">
                <Card>
                    <CardHeader>
                        <CardTitle>Job Settings</CardTitle>
                        <CardDescription>Configure job management settings</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div v-for="setting in jobSettings" :key="setting.id" class="space-y-2">
                            <Label :for="setting.key">
                                {{ setting.description || setting.key }}
                            </Label>
                            
                            <Switch
                                v-if="setting.type === 'boolean'"
                                :id="setting.key"
                                :checked="getValue(setting)"
                                @update:checked="(val: boolean) => updateValue(setting, val)"
                            />
                            
                            <Input
                                v-else
                                :id="setting.key"
                                :model-value="setting.value"
                                @update:model-value="(val: string | number) => updateValue(setting, val)"
                            />
                        </div>
                        
                        <Button @click="saveSettings(jobSettings)" :disabled="form.processing">
                            <Save class="mr-2 h-4 w-4" />
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </Button>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Notification Settings -->
            <TabsContent value="notifications">
                <Card>
                    <CardHeader>
                        <CardTitle>Notification Settings</CardTitle>
                        <CardDescription>Configure notification preferences</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div v-for="setting in notificationSettings" :key="setting.id" class="space-y-2">
                            <Label :for="setting.key">
                                {{ setting.description || setting.key }}
                            </Label>
                            
                            <Switch
                                v-if="setting.type === 'boolean'"
                                :id="setting.key"
                                :checked="getValue(setting)"
                                @update:checked="(val: boolean) => updateValue(setting, val)"
                            />
                        </div>
                        
                        <Button @click="saveSettings(notificationSettings)" :disabled="form.processing">
                            <Save class="mr-2 h-4 w-4" />
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </Button>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Inventory Settings -->
            <TabsContent value="inventory">
                <Card>
                    <CardHeader>
                        <CardTitle>Inventory Settings</CardTitle>
                        <CardDescription>Configure inventory management settings</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div v-for="setting in inventorySettings" :key="setting.id" class="space-y-2">
                            <Label :for="setting.key">
                                {{ setting.description || setting.key }}
                            </Label>
                            
                            <Switch
                                v-if="setting.type === 'boolean'"
                                :id="setting.key"
                                :checked="getValue(setting)"
                                @update:checked="(val: boolean) => updateValue(setting, val)"
                            />
                            
                            <Input
                                v-else-if="setting.type === 'integer'"
                                :id="setting.key"
                                type="number"
                                :model-value="setting.value"
                                @update:model-value="(val: string | number) => updateValue(setting, val)"
                            />
                        </div>
                        
                        <Button @click="saveSettings(inventorySettings)" :disabled="form.processing">
                            <Save class="mr-2 h-4 w-4" />
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </Button>
                    </CardContent>
                </Card>
            </TabsContent>
        </Tabs>
    </div>
    </AppLayout>
</template>
