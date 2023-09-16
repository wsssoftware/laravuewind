<template>
  <transition
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0">
    <ListboxOptions class="lvw-select-options">
      <ListboxOption
          v-slot="{ active, selected }"
          v-for="choice in choices"
          :key="choice.key"
          :value="choice.key"
          as="template">
        <component v-if="component" :is="component" :selected="selected" :choice="choice" :active="active"/>
        <Option v-else :theme="theme" :selected="selected" :choice="choice" :active="active"/>
      </ListboxOption>
    </ListboxOptions>
  </transition>
</template>

<script lang="ts">
import {Component, defineComponent} from "vue";
import {PropType} from "vue/dist/vue";
import {SelectChoice} from "../InputTypes";
import {ListboxOptions, ListboxOption} from '@headlessui/vue'
import Option from "./Option.vue";

export default defineComponent({
  name: "Options",
  components: {
    Option,
    ListboxOption,
    ListboxOptions,
  },
  props: {
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    component: Object as PropType<Component>,
    theme: {type: String, required: true},
  }
})
</script>

<style scoped lang="scss">
.lvw-select-options {
  @apply absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm;
}
</style>