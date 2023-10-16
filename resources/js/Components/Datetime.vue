<template>
    <time :datetime="timestamp">
        {{ asDate ? formattedDate : asTime ? formattedTime : formatted }}
    </time>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";

export default defineComponent({
    name: "Datetime",
    props: {
        asDate: {
            type: Boolean,
            default: false,
        },
        asTime: {
            type: Boolean,
            default: false,
        },
        datetime: {
            required: true,
            type: String,
        },
        locale: String,
        options: {
            type: Object as PropType<Intl.DateTimeFormatOptions>,
        }
    },
    computed: {
        date(): Date {
            return new Date(this.datetime);
        },
        formattedDate() {
            return this.date.toLocaleDateString(this.finalLocale, this.options);
        },
        formattedTime() {
            return this.date.toLocaleTimeString(this.finalLocale, this.options);
        },
        formatted() {
            return this.date.toLocaleString(this.finalLocale, this.options);
        },
        finalLocale(): string {
            let locale = this.locale;
            if (!locale) {
                locale = this.$lvw?.locale ?? 'en';
            }
            return locale.replace('_', '-');
        },
        timestamp(): string {
            return this.date.toISOString().slice(0, 10);
        },
    }
});
</script>

<style scoped>

</style>
