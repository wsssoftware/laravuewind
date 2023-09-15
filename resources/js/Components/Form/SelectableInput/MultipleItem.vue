<template>
  <div class="flex justify-start items-center gap-1 flex-wrap">
    <span
        v-for="choice in choices"
        :class="['lvw-select-multiple-item', `lvw-${theme}`]"
        :key="choice.key">
      <span v-tooltip="typeof choice.value === 'string' && choice.value.length > 20 ? choice.value : false" class="truncate">
        {{ typeof choice.value === 'string' && choice.value.length > 20 ?
          choice.value.substring(0, 20) + '...' :
          choice.value }}
      </span>
      <button class="w-3.5 h-3.5 p-0.5 -my-0.5 group hover:p-[0.10rem]" type="button" @click.prevent="$emit('remove', choice)">
        <XmarkLarge class="h-full w-full fill-red-700 group-hover:fill-red-900"/>
      </button>
    </span>
  </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {SelectChoice} from "../InputTypes";
import XmarkLarge from "../../Icons/XmarkLarge.vue";
import Tooltip from "../../../Directives/Tooltip";

export default defineComponent({
  name: "MultipleItem",
  components: {XmarkLarge},
  directives: {
    Tooltip,
  },
  props: {
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    theme: {type: String, required: true},
  },
  emits: {
    remove(choice: SelectChoice): boolean {
      return true;
    }
  },
})
</script>

<style scoped lang="scss">
.lvw-select-multiple-item {
  @apply inline-flex items-center gap-x-0.5 rounded-md px-1 py-0.5 text-xs font-medium;
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