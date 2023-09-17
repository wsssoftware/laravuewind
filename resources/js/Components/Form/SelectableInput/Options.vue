<template>
  <transition
      leave-active-class="transition duration-100 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0">
    <ListboxOptions class="lvw-select-options">
      <SearchInput
          v-if="searchable"
          v-model="search"
          :theme="theme"/>

      <SearchNoResultsFound
          v-if="searchable && search !== '' && filtered.length === 0"
          :theme="theme"/>

      <ListboxOption
          v-slot="{ active, selected }"
          v-for="choice in filtered"
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
import SearchInput from "./SearchInput.vue";
import SearchNoResultsFound from "./SearchNoResultsFound.vue";

export default defineComponent({
  name: "Options",
  components: {
    SearchNoResultsFound,
    SearchInput,
    Option,
    ListboxOption,
    ListboxOptions,
  },
  props: {
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    component: Object as PropType<Component>,
    open: {type: Boolean, required: true},
    searchable: {type: Boolean, required: true},
    theme: {type: String, required: true},
  },
  data() {
    return {
      search: null,
      filtered: null,
    }
  },
  beforeMount() {
    this.resetSearch();
  },
  beforeUpdate() {
    this.resetSearch();
  },
  methods: {
    resetSearch() {
      if (this.searchable && this.open) {
        this.search = ''
        this.filtered = this.choices
      }
    },
    stringChoice(value: string | number | boolean): string {
      return typeof value === 'string'
          ? value
          : typeof value === 'number'
              ? value.toString()
              : typeof value === 'boolean'
                  ? value ? 'true' : 'false'
                  : ''
    }
  },
  watch: {
    search(value: string) {
      this.filtered = value === ''
          ? this.choices
          : this.choices.filter((choice: SelectChoice) => this.stringChoice(choice.value)
              .toLowerCase()
              .replace(/\s+/g, '')
              .includes(value.toLowerCase().replace(/\s+/g, ''))
          )
    }
  },
})
</script>

<style scoped lang="scss">
.lvw-select-options {
  @apply absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm;
}
</style>