<template>
  <label :for="id" :class="[hasError ? 'text-red-600' : 'text-gray-900', 'flex items-center gap-1 text-sm font-medium leading-6']">
    <slot/>
    <span class="text-red-600" v-if="required">*</span>
    <Question v-if="help" v-tooltip="help" class="inline w-3.5 h-3.5 bg-sky-600 rounded-full p-[0.2em] fill-white"/>
  </label>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import Tooltip from "../../Directives/Tooltip";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import Question from "../Icons/Question.vue";

export default defineComponent({
  name: "InputLabel",
  components: {Question},
  props: {
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    help: String,
    required: Boolean,
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

<style scoped>

</style>