<template>
  <div>
    <label for="email" class="sr-only">Search</label>
    <input
        v-model="value"
        type="search"
        :class="['lvw-input', `lvw-${theme}`]"
        :placeholder="searchPlaceholder ?? 'What are you looking for?'" />
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "SearchInput",
  props: {
    modelValue: {type: String, required: true},
    searchPlaceholder: String,
    theme: {type: String, required: true},
  },
  emits: ['update:modelValue'],
  computed: {
    value: {
      get(): string {
        return this.modelValue
      },
      set(value: string) {
        this.$emit('update:modelValue', value)
      }
    }
  }
})
</script>

<style scoped lang="scss">
.lvw-input {
  @apply form-input block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6;
  @apply -my-1 mb-2 rounded-b-none;
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