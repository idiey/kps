<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { useLocale } from '@/composables/useLocale';
import { computed } from 'vue';

const { locale, availableLocales, setLocale, t } = useLocale();

const hasBothLocales = computed(
    () => availableLocales.value.includes('ms') && availableLocales.value.includes('en'),
);
</script>

<template>
    <div v-if="hasBothLocales" class="flex items-center gap-2">
        <!-- <Label class="text-xs text-muted-foreground">{{ t('language.label', 'Language') }}</Label> -->
        <div class="inline-flex items-center rounded-md border border-input bg-background p-0.5">
            <Button
                type="button"
                size="sm"
                variant="ghost"
                :aria-pressed="locale === 'ms'"
                class="h-7 rounded-sm px-2 text-xs font-semibold"
                :class="locale === 'ms' ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'text-muted-foreground hover:text-foreground'"
                @click="setLocale('ms')"
            >
                <span v-if="locale === 'ms'" class="mr-1">●</span>
                BM
            </Button>
            <Button
                type="button"
                size="sm"
                variant="ghost"
                :aria-pressed="locale === 'en'"
                class="h-7 rounded-sm px-2 text-xs font-semibold"
                :class="locale === 'en' ? 'bg-primary text-primary-foreground hover:bg-primary/90' : 'text-muted-foreground hover:text-foreground'"
                @click="setLocale('en')"
            >
                <span v-if="locale === 'en'" class="mr-1">●</span>
                EN
            </Button>
        </div>
    </div>
</template>
