<template>
  <div :class="['lvw-no-results-found', `lvw-${theme}`]">
    <EmptySet class="w-5 h-5"/>
    {{ $lvw.translate('form.select.noChoices') }}
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import EmptySet from "../../Icons/EmptySet.vue";

export default defineComponent({
  name: "NoChoices",
  components: {EmptySet},
  props: {
    theme: {type: String, required: true},
  },
})
</script>

<style scoped lang="scss">
.lvw-no-results-found {
  @apply w-full flex justify-center items-center gap-x-2 py-2 font-medium text-sm;
  &.lvw-gray {
    @apply text-gray-700/70 fill-gray-700
  }
  &.lvw-green {
    @apply text-green-700/70 fill-green-700
  }
  &.lvw-indigo {
    @apply text-indigo-700/70 fill-indigo-700
  }
  &.lvw-primary {
    @apply text-primary-700/70 fill-primary-700
  }
  &.lvw-slate {
    @apply text-slate-700/70 fill-slate-700
  }
}
</style>