import '../css/app.css';

import ui from '@nuxt/ui/vue-plugin';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, defineComponent, h } from 'vue';
import UApp from '@nuxt/ui/components/App.vue';
import { initializeTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'KPS';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const RootApp = defineComponent({
            render: () => h(UApp, {}, { default: () => h(App, props) }),
        });
        createApp(RootApp)
            .use(plugin)
            .use(ui)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
