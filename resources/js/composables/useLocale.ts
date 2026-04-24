import type { AppPageProps } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { messages, type Locale } from '@/i18n/messages';

const DEFAULT_LOCALE: Locale = 'ms';

export const useLocale = () => {
    const page = usePage<AppPageProps<{ locale?: string; locales?: string[] }>>();

    const locale = computed<Locale>(() => {
        const raw = page.props.locale;
        return raw === 'en' ? 'en' : DEFAULT_LOCALE;
    });

    const availableLocales = computed<Locale[]>(() => {
        const list = page.props.locales ?? [DEFAULT_LOCALE, 'en'];
        return list.filter((entry): entry is Locale => entry === 'ms' || entry === 'en');
    });

    const t = (key: string, fallback?: string): string => {
        return (
            messages[locale.value]?.[key] ??
            messages[DEFAULT_LOCALE]?.[key] ??
            fallback ??
            key
        );
    };

    const setLocale = (nextLocale: Locale): void => {
        if (nextLocale === locale.value) {
            return;
        }

        router.post(
            '/locale',
            { locale: nextLocale },
            {
                preserveScroll: true,
                replace: true,
            },
        );
    };

    return {
        locale,
        availableLocales,
        t,
        setLocale,
    };
};
