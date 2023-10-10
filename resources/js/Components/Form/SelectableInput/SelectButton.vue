<template>
    <ListboxButton :disabled="disabled" :class="[
      'lvw-input',
      {'lvw-open': open},
      hasError ? 'lvw-red' : `lvw-${theme}`
      ]">
        <Selected
            @remove="remove"
            :class="{'lvw-placeholder': placeholder && choice === undefined}"
            :choice="choice === undefined ? {key:null, value: placeholder} : choice"
            :multiple="multiple"
            :theme="theme"/>
        <div class="flex justify-center items-center gap-x-1">
            <ClearButton :show="clearable && choice !== undefined" @click.prevent="clear"/>
            <AnglesUpDown class="lvw-angles-icon" aria-hidden="true"/>
        </div>
    </ListboxButton>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {ListboxButton} from '@headlessui/vue'
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import AnglesUpDown from "../../Icons/AnglesUpDown.vue";
import {SelectChoice} from "../InputTypes";
import ClearButton from "./ClearButton.vue";
import Selected from "./Selected.vue";

export default defineComponent({
    name: "SelectButton",
    components: {
        Selected,
        ClearButton,
        AnglesUpDown,
        ListboxButton,
    },
    props: {
        clearable: {type: Boolean, required: true},
        choices: {type: Object as PropType<SelectChoice[]>, required: true},
        disabled: Boolean,
        field: {type: String, required: true},
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
        multiple: {type: Boolean, required: true},
        open: {type: Boolean, required: true},
        placeholder: String,
        theme: {type: String, required: true},
    },
    computed: {
        choice(): undefined | SelectChoice | SelectChoice[] {
            if (this.multiple) {
                let result = this.choices.filter((choice: SelectChoice) => this.form[this.field].includes(choice.key));
                return result.length > 0 ? result : undefined;
            }
            return this.choices.find((choice: SelectChoice) => choice.key == this.form[this.field]);
        },
        hasError(): boolean {
            return !!this.form.errors[this.field];
        }
    },
    methods: {
        remove(choice: SelectChoice): void {
            if (Array.isArray(this.form[this.field])) {
                this.form[this.field] = this.form[this.field].filter((key: number) => key !== choice.key);
                return;
            }
        },
        clear(): void {
            if (this.multiple) {
                this.form[this.field] = [];
                return;
            }
            this.form[this.field] = '';
        }
    }
});
</script>

<style lang="scss" scoped>
.lvw-input {
    @apply form-input block w-full rounded-md border-0 py-1.5 px-2 shadow-sm ring-1 ring-inset sm:text-sm sm:leading-6;
    @apply min-h-[2.572em] flex justify-between items-center gap-x-1 cursor-default;
    @apply disabled:bg-gray-500/20 disabled:cursor-not-allowed;
    &.lvw-open {
        @apply ring-2;
    }

    .lvw-angles-icon {
        @apply h-3 w-3;
    }

    &.lvw-gray {
        @apply text-gray-800 ring-gray-500 focus:ring-gray-600;
        .lvw-placeholder {
            @apply text-gray-800/60;
        }

        .lvw-angles-icon {
            @apply fill-gray-800/75;
        }
    }

    &.lvw-green {
        @apply text-green-900 ring-green-500 focus:ring-green-600;
        .lvw-placeholder {
            @apply text-green-800/60;
        }

        .lvw-angles-icon {
            @apply fill-green-800/75;
        }
    }

    &.lvw-indigo {
        @apply text-indigo-900 ring-indigo-500 focus:ring-indigo-600;
        .lvw-placeholder {
            @apply text-indigo-800/60;
        }

        .lvw-angles-icon {
            @apply fill-indigo-800/75;
        }
    }

    &.lvw-primary {
        @apply text-primary-900 ring-primary-500 focus:ring-primary-600;
        .lvw-placeholder {
            @apply text-primary-800/60;
        }

        .lvw-angles-icon {
            @apply fill-primary-800/75;
        }
    }

    &.lvw-slate {
        @apply text-slate-900 ring-slate-500 focus:ring-slate-600;
        .lvw-placeholder {
            @apply text-slate-800/60;
        }

        .lvw-angles-icon {
            @apply fill-slate-800/75;
        }
    }

    &.lvw-red {
        @apply text-red-700 ring-red-700 focus:ring-red-700;
        .lvw-placeholder {
            @apply text-red-800/60;
        }

        .lvw-angles-icon {
            @apply fill-red-800/75;
        }
    }
}
</style>
