<template>
  <li :class="['lvw-select-option', `lvw-${theme}`, {'lvw-selected': selected}, {'lvw-active' : active}]">
    <span :class="['lvw-value', {'lvw-selected': selected}]">
      {{ value }}
    </span>
    <span v-if="selected" class="lvw-check">
      <Check class="h-4 w-4" aria-hidden="true"/>
    </span>
  </li>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import Check from "../../Icons/Check.vue";
import {SelectChoice} from "../InputTypes";

export default defineComponent({
  name: "Option",
  components: {Check},
  props: {
    active: {type: Boolean, required: true},
    choice: {type: Object as PropType<SelectChoice>, required: true},
    selected: {type: Boolean, required: true},
    theme: {type: String, required: true},
  },
  computed: {
    value(): string {
      if (typeof this.choice.value === 'number') {
        return this.choice.value.toString();
      }
      if (typeof this.choice.value === 'boolean') {
        return this.choice.value ? 'true' : 'false';
      }
      return this.choice.value;
    }
  }
})
</script>

<style scoped lang="scss">
.lvw-select-option {
  @apply relative cursor-default select-none py-2 pl-10 pr-4;

  .lvw-value {
    @apply block truncate font-normal;
    &.lvw-selected {
      @apply font-medium
    }
  }

  .lvw-check {
    @apply absolute inset-y-0 left-0 flex items-center pl-3
  }

  &.lvw-gray {
    @apply text-gray-900;
    &.lvw-selected {
      @apply bg-gray-600/5
    }

    &.lvw-active {
      @apply bg-gray-600/40
    }

    .lvw-check {
      @apply fill-gray-700
    }
  }

  &.lvw-green {
    @apply text-green-900;
    &.lvw-selected {
      @apply bg-green-600/5
    }

    &.lvw-active {
      @apply bg-green-600/40
    }

    .lvw-check {
      @apply fill-green-700
    }
  }

  &.lvw-indigo {
    @apply text-indigo-900;
    &.lvw-selected {
      @apply bg-indigo-600/5
    }

    &.lvw-active {
      @apply bg-indigo-600/40
    }

    .lvw-check {
      @apply fill-indigo-700
    }
  }

  &.lvw-primary {
    @apply text-primary-900;
    &.lvw-selected {
      @apply bg-primary-600/5
    }

    &.lvw-active {
      @apply bg-primary-600/40
    }

    .lvw-check {
      @apply fill-primary-700
    }
  }

  &.lvw-slate {
    @apply text-slate-900;
    &.lvw-selected {
      @apply bg-slate-600/5
    }

    &.lvw-active {
      @apply bg-slate-600/40
    }

    .lvw-check {
      @apply fill-slate-700
    }
  }
}
</style>