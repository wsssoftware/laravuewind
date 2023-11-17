<template>
  <textarea
      v-if="type === 'textarea'"
      :id="id"
      v-model="value"
      v-maskito="maskito"
      :class="['lvw-input', hasError ? 'lvw-red' : `lvw-${theme}`]"/>
    <input
        v-else
        :type="type"
        :id="id"
        v-model="value"
        v-maskito="maskito"
        :class="['lvw-input', hasError ? 'lvw-red' : `lvw-${theme}`]"/>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import {MaskitoOptions} from "@maskito/core";
import Maskito from "../../Directives/Maskito";

export default defineComponent({
    name: "VModelFillableInput",
    props: {
        id: String,
        field: {type: String, required: true},
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
        maskito: Object as PropType<MaskitoOptions>,
        modelValue: [String, Number],
        theme: String,
        type: String,
    },
    emits: ['update:modelValue'],
    directives: {
        Maskito,
    },
    computed: {
        hasError(): boolean {
            return !!this.form.errors[this.field];
        },
        value: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        }
    },
});
</script>

<style lang="scss" scoped>
.lvw-input {
    @apply form-input block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6;
    @apply disabled:bg-gray-500/20 disabled:cursor-not-allowed;
    &.lvw-gray {
        @apply text-gray-800 ring-gray-500 placeholder:text-gray-800/60 focus:ring-gray-600
    }

    &.lvw-green {
        @apply text-green-900 ring-green-500 placeholder:text-green-800/60 focus:ring-green-600
    }

    &.lvw-indigo {
        @apply text-indigo-900 ring-indigo-500 placeholder:text-indigo-800/60 focus:ring-indigo-600
    }

    &.lvw-primary {
        @apply text-primary-900 ring-primary-500 placeholder:text-primary-800/60 focus:ring-primary-600
    }

    &.lvw-slate {
        @apply text-slate-900 ring-slate-500 placeholder:text-slate-800/60 focus:ring-slate-600
    }

    &.lvw-red {
        @apply text-red-700 ring-red-700 placeholder:text-red-700/60 focus:ring-red-700
    }
}
</style>
