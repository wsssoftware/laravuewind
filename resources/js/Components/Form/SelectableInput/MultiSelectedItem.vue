<template>
<span
    :class="['lvw-select-multiple-item', `lvw-${theme}`]">
      <span v-tooltip="value.length > 20 ? value : false" class="truncate">
        {{ value.length > 20 ? value.substring(0, 20) + '...' : value }}
      </span>
      <button class="w-3.5 h-3.5 p-0.5 -my-0.5 group hover:p-[0.10rem]" type="button" @click.prevent="$emit('remove', choice)">
        <XmarkLarge class="h-full w-full fill-red-700 group-hover:fill-red-900"/>
      </button>
    </span>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import XmarkLarge from "../../Icons/XmarkLarge.vue";
import {PropType} from "vue/dist/vue";
import {SelectChoice} from "../InputTypes";
import Tooltip from "../../../Directives/Tooltip";

export default defineComponent({
  name: "MultiSelectedItem",
  components: {XmarkLarge},
  directives: {
    Tooltip,
  },
  props: {
    choice: {type: Object as PropType<SelectChoice>, required: true},
    theme: {type: String, required: true},
  },
  emits: {
    remove(choice: SelectChoice): boolean {
      return true;
    }
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
.lvw-select-multiple-item {
  @apply inline-flex items-center gap-x-0.5 rounded-md px-1.5 py-0.5 text-xs font-medium;
  &.lvw-gray {
    @apply bg-gray-600/30 text-gray-800
  }
  &.lvw-green {
    @apply bg-green-600/40 text-green-800
  }
  &.lvw-indigo {
    @apply bg-indigo-600/40 text-indigo-800
  }
  &.lvw-primary {
    @apply bg-primary-600/40 text-primary-800
  }
  &.lvw-slate {
    @apply bg-slate-600/40 text-slate-800
  }
}
</style>