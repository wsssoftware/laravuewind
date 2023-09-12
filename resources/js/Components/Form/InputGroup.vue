<template>
  <div v-bind="parentAttrs">
    <slot v-if="labelSlotExists" :id="finalId" :field="field" :form="form" :help="help" :label="label" :required="required" name="label"/>
    <InputLabel v-else-if="!!label" :id="finalId" :field="field" :form="form" :help="help" :required="required">{{ label }}</InputLabel>
    <div class="mt-2">
      <slot v-if="inputSlotExists" :id="finalId" :field="field" :form="form" :type="type" name="input"/>
      <FillableInput v-bind="$attrs" v-else v-model="form[field]" :id="finalId" :field="field" :form="form" :type="type"/>
    </div>
    <InputHelp/>
  </div>
</template>

<script lang="ts">
import FillableInput from "./FillableInput.vue";
import InputLabel from "./InputLabel.vue";
import InputHelp from "./InputHelp.vue";
import type { PropType } from 'vue'
import {defineComponent} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";

export default defineComponent({
  inheritAttrs: false,
  name: "InputGroup",
  components: {
    FillableInput,
    InputHelp,
    InputLabel
  },
  props: {
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    help: String,
    label: String,
    parentAttrs: Object,
    required: Boolean,
    type: String,
  },
  computed: {
    finalId(): string {
      return this.id || 'input-' + Math.random().toString(16).slice(2);
    },
    inputSlotExists(): boolean {
      return !!this.$slots.label;
    },
    labelSlotExists(): boolean {
      return !!this.$slots.input;
    },
  },
  beforeMount() {
    if (this.form[this.field] === undefined) {
      throw new Error(`The form field "${this.field}" is missing from the component's form.`);
    }
  }
})
</script>

<style scoped>

</style>