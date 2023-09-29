<template>
    <Listbox as="div" class="relative" v-model="form[field]" v-slot="{open}" :multiple="multiple">
        <SelectButton
            :clearable="clearable"
            :choices="choices"
            :field="field"
            :form="form"
            :multiple="multiple"
            :open="open"
            :placeholder="placeholder"
            :theme="theme"/>

      <Options
          :choices="choices"
          :component="component"
          :open="open"
          :searchable="searchable"
          :theme="theme"/>
    </Listbox>
</template>

<script lang="ts">
import {Component, defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import {SelectChoice} from "./InputTypes";
import {Listbox} from '@headlessui/vue'
import SelectButton from "./SelectableInput/SelectButton.vue";
import Options from "./SelectableInput/Options.vue";

export default defineComponent({
  name: "SelectableInput",
  components: {
    Options,
    SelectButton,
    Listbox,
  },
  props: {
    clearable: {type: Boolean, required: true},
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    component: Object as PropType<Component>,
    id: {type: String, required: true},
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    multiple: {type: Boolean, required: true},
    placeholder: String,
    searchable: {type: Boolean, required: true},
    theme: {type: String, required: true},
  },
  beforeMount() {
    if (Array.isArray(this.form[this.field])) {
      this.form[this.field] = this.form[this.field].map((value) => String(value));
    } else {
      this.form[this.field] = String(this.form[this.field]);
    }
  }
});
</script>

<style scoped>

</style>