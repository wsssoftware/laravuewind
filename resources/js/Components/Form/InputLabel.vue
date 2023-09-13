<template>
  <label :for="id" :class="['lvw-input-label', hasError ? 'lvw-red' :  `lvw-${theme}`]">
    <slot/>
    <Asterisk v-if="required" class="inline w-2 h-2 fill-red-600"/>
    <Question v-if="help" v-tooltip="help" class="inline w-3.5 h-3.5 fill-sky-700"/>
  </label>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import Tooltip from "../../Directives/Tooltip";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import Asterisk from "../Icons/Asterisk.vue";
import Question from "../Icons/Question.vue";

export default defineComponent({
  name: "InputLabel",
  components: {Asterisk, Question},
  props: {
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    help: String,
    required: Boolean,
    theme: String,
  },
  directives: {
    Tooltip,
  },
  computed: {
    hasError(): boolean {
      return !!this.form.errors[this.field];
    }
  }
});
</script>

<style lang="scss" scoped>
.lvw-input-label {
  @apply flex items-center gap-1 text-sm font-medium leading-6;
  &.lvw-gray {
    @apply text-gray-800
  }
  &.lvw-green {
    @apply text-green-900
  }
  &.lvw-indigo {
    @apply text-indigo-900
  }
  &.lvw-primary {
    @apply text-primary-900
  }
  &.lvw-slate {
    @apply text-slate-900
  }
  &.lvw-red {
    @apply text-red-700
  }
}
</style>