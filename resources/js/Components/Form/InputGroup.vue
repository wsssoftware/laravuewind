<template>
  <div v-bind="parentAttrs">
    <slot v-if="labelSlotExists" :id="finalId" :field="field" :form="form" :help="help" :label="label" :required="required" :theme="theme" name="label"/>
    <InputLabel v-else-if="!!label" :id="finalId" :field="field" :form="form" :help="help" :required="required" :theme="theme">{{ label }}</InputLabel>
    <div class="mt-1">
      <slot v-if="inputSlotExists" :id="finalId" :field="field" :form="form" :theme="theme" :type="type" name="input"/>
      <FillableInput v-bind="$attrs" v-else v-model="form[field]" :id="finalId" :field="field" :form="form" :theme="theme" :type="type"/>
    </div>
    <slot v-if="feedbackSlotExists" :id="finalId" :field="field" :form="form" :showMaxLength="showMaxLength" :theme="theme" name="feedback"/>
    <InputFeedback v-else :id="finalId" :feedback="feedback" :field="field" :form="form" :show-max-length="showMaxLength" :theme="theme"/>
  </div>
</template>

<script lang="ts">
import FillableInput from "./FillableInput.vue";
import InputLabel from "./InputLabel.vue";
import InputFeedback from "./InputFeedback.vue";
import type { PropType } from 'vue'
import {defineComponent} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";

export default defineComponent({
  inheritAttrs: false,
  name: "InputGroup",
  components: {
    FillableInput,
    InputFeedback,
    InputLabel
  },
  props: {
    id: String,
    feedback: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    help: String,
    label: String,
    parentAttrs: Object,
    required: Boolean,
    showMaxLength: {
      type: Boolean,
      default: true,
    },
    theme: {
      type: String,
      default: 'primary',
      validator(value: string): boolean {
        return ['gray', 'green', 'indigo', 'primary', 'slate'].includes(value);
      }
    },
    type: String,
  },
  computed: {
    finalId(): string {
      return this.id || 'input-' + Math.random().toString(16).slice(2);
    },
    feedbackSlotExists(): boolean {
      return !!this.$slots.feedback;
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