<template>
  <div v-bind="parentAttrs">
    <slot v-if="labelSlotExists" :id="finalId" :field="field" :form="form" :help="help" :label="label" :required="required" :theme="theme" name="label"/>
    <InputLabel v-else-if="!!label" :id="finalId" :field="field" :form="form" :help="help" :required="required" :theme="theme">{{ label }}</InputLabel>
    <div class="mt-1">
      <slot
          v-if="inputSlotExists"
          :id="finalId"
          :field="field"
          :form="form"
          :maskito="maskito"
          :theme="theme"
          :type="type"
          name="input"/>
      <FillableInput
          v-else-if="isFillable && typeof  type === 'string'"
          v-bind="$attrs"
          v-model="form[field]"
          :id="finalId"
          :field="field"
          :form="form"
          :maskito="maskito"
          :theme="theme"
          :type="type"/>
      <SelectableInput
          v-else-if="isSelectable"
          v-bind="$attrs"
          v-model="form[field]"
          :clearable="type.clearable ?? false"
          :choices="type.choices"
          :component="type.optionComponent"
          :id="finalId"
          :field="field"
          :form="form"
          :multiple="type.multiple ?? false"
          :searchable="type.searchable ?? false"
          :theme="theme"/>
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
import InputTypes, {Fillable, SelectOptions} from "./InputTypes";
import {MaskitoOptions} from "@maskito/core";
import SelectableInput from "./SelectableInput.vue";

type sa = string;

export default defineComponent({
  inheritAttrs: false,
  name: "InputGroup",
  components: {
    SelectableInput,
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
    maskito: Object as PropType<MaskitoOptions>,
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
    type: {
      type: [String, Object] as PropType<string|SelectOptions>,
      default: 'text',
      validator(value: string|SelectOptions): boolean {
        return InputTypes.includes(value) || (typeof value === 'object' && Array.isArray(value.choices));
      }
    },
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
    isFillable(): boolean {
      return Fillable.includes(this.type);
    },
    isSelectable(): boolean {
      return typeof this.type === 'object' && Array.isArray(this.type.choices)
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