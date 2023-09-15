<template>
    <Listbox as="div" class="relative" v-model="form[field]" v-slot="{open}">
        <SelectButton
            :clearable="clearable"
            :choices="choices"
            :field="field"
            :form="form"
            :open="open"
            :placeholder="placeholder"
            :theme="theme"/>

        <transition
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
          <ListboxOptions
              class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
            <ListboxOption
                v-slot="{ active, selected }"
                v-for="choice in choices"
                :key="choice.key"
                :value="choice.key"
                as="template"
            >
              <li
                  :class="[
                  active ? 'bg-amber-100 text-amber-900' : 'text-gray-900',
                  'relative cursor-default select-none py-2 pl-10 pr-4',
                ]"
              >
                <span
                    :class="[
                    selected ? 'font-medium' : 'font-normal',
                    'block truncate',
                  ]"
                >{{ choice.value }}</span
                >
                <span
                    v-if="selected"
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                >
<!--                  <CheckIcon class="h-5 w-5" aria-hidden="true" />-->
                </span>
              </li>
            </ListboxOption>
          </ListboxOptions>
        </transition>
    </Listbox>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import {SelectChoice} from "./InputTypes";
import {
  Listbox,
  ListboxLabel,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue'
import SelectButton from "./SelectableInput/SelectButton.vue";

export default defineComponent({
  name: "SelectableInput",
  components: {
    SelectButton,
    Listbox,
    ListboxLabel,
    ListboxOptions,
    ListboxOption,
  },
  props: {
    clearable: Boolean,
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    multiple: Boolean,
    placeholder: String,
    searchable: Boolean,
    theme: String,
  }
});
</script>

<style scoped>

</style>