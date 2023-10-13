<template>
    <div class="relative flex items-start">
        <div class="flex h-6 items-center">
            <input
                :id="finalId"
                v-bind="$attrs"
                :aria-describedby="feedback ? feedbackId : undefined"
                v-model="form[field]"
                type="checkbox"
                :class="['lvw-checkbox', hasError ? 'lvw-red' : `lvw-${theme}`]"/>
        </div>
        <div class="ml-3 text-sm leading-6">
            <label :for="finalId" :class="['lvw-checkbox-label', hasError ? 'lvw-red' :  `lvw-${theme}`]">
                {{ label }}
                <Asterisk v-if="required" class="inline w-2 h-2 fill-red-600"/>
                <Question v-if="help" v-tooltip="help" class="inline w-3.5 h-3.5 fill-sky-700"/>
            </label>
            <p v-if="feedback || hasError" :id="feedbackId"
               :class="['lvw-checkbox-feedback', hasError ? 'lvw-red' : `lvw-${theme}`]">
                <template v-if="hasError">
                    {{ form.errors[field] }}
                </template>
                <template v-else>
                    {{ feedback }}
                </template>
            </p>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {PropType} from "vue/dist/vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import Asterisk from "../Icons/Asterisk.vue";
import Tooltip from "../../Directives/Tooltip";
import Question from "../Icons/Question.vue";

export default defineComponent({
    inheritAttrs: false,
    name: "CheckboxGroup",
    components: {Question, Asterisk},
    props: {
        id: String,
        feedback: String,
        field: {type: String, required: true},
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
        help: String,
        label: {
            type: String,
            required: true,
        },
        required: Boolean,
        theme: {
            type: String,
            default: 'primary',
            validator(value: string): boolean {
                return ['gray', 'green', 'indigo', 'primary', 'slate'].includes(value);
            }
        },
    },
    directives: {
        Tooltip,
    },
    computed: {
        finalId(): string {
            return this.id || 'checkbox-' + Math.random().toString(16).slice(2);
        },
        feedbackId(): string {
            return this.id || 'feedback-' + Math.random().toString(16).slice(2);
        },
        hasError(): boolean {
            return !!this.form.errors[this.field];
        }
    },
    beforeMount() {
        if (this.form[this.field] === undefined) {
            throw new Error(`The form field "${this.field}" is missing from the component's form.`);
        }
    }
});
</script>

<style lang="scss" scoped>
.lvw-checkbox {
    @apply form-checkbox h-4 w-4 rounded;
    @apply disabled:bg-gray-500/20 disabled:cursor-not-allowed;
    &.lvw-gray {
        @apply border-gray-600/40 text-gray-600 focus:ring-gray-600
    }

    &.lvw-green {
        @apply border-green-600/40 text-green-600 focus:ring-green-600
    }

    &.lvw-indigo {
        @apply border-indigo-600/40 text-indigo-600 focus:ring-indigo-600
    }

    &.lvw-primary {
        @apply border-primary-600/40 text-primary-600 focus:ring-primary-600
    }

    &.lvw-slate {
        @apply border-slate-600/40 text-slate-600 focus:ring-slate-600
    }

    &.lvw-red {
        @apply border-red-600/40 text-red-600 focus:ring-red-600
    }
}

.lvw-checkbox-label {
    @apply flex items-center gap-1 text-sm font-medium leading-6;
    &.lvw-gray {
        @apply text-gray-900;
    }

    &.lvw-green {
        @apply text-green-900;
    }

    &.lvw-indigo {
        @apply text-indigo-900;
    }

    &.lvw-primary {
        @apply text-primary-900;
    }

    &.lvw-slate {
        @apply text-slate-900
    }

    &.lvw-red {
        @apply text-red-700
    }
}

.lvw-checkbox-feedback {
    &.lvw-gray {
        @apply text-gray-500;
    }

    &.lvw-green {
        @apply text-green-500;
    }

    &.lvw-indigo {
        @apply text-indigo-500;
    }

    &.lvw-primary {
        @apply text-primary-500;
    }

    &.lvw-slate {
        @apply text-slate-500;
    }

    &.lvw-red {
        @apply text-red-500;
    }
}
</style>
