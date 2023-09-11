<template>
  <input
      :type="type"
      :id="id"
      v-model="form[field]"
      class="form-input block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" />
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import FillableTypes from "./FillableTypes";
export default defineComponent({
  name: "FillableInput",
  props: {
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    type: {
      type: String,
      default: 'text',
      validator(value: string): boolean {
        return FillableTypes.includes(value);
      }
    }
  }
});
</script>

<style scoped>

</style>