<template>
  <transition
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0">
    <button v-if="show" type="button" aria-label="clear current selection">
      <XmarkLarge class="lvw-xmark-icon" aria-hidden="true"/>
    </button>
  </transition>
</template>

<script lang="ts">
import XmarkLarge from "../../Icons/XmarkLarge.vue";
import {defineComponent} from "vue";

export default defineComponent({
  name: "ClearButton",
  components: {XmarkLarge},
  props: {
    show: Boolean,
  },
})
</script>

<style lang="scss" scoped>
.lvw-xmark-icon {
  @apply h-5 w-5 p-1 fill-red-500 transition-all duration-200 hover:fill-red-700 hover:p-[0.20rem];
}
</style>