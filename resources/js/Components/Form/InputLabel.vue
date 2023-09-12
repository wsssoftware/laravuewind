<template>
  <label :for="id" :class="[hasError ? 'text-red-600' : 'text-gray-900', 'block text-sm font-medium leading-6']">
    <slot/>
    <span class="text-red-600" v-if="required">&nbsp;*</span>
    <div class="flex gap-2">
      <span v-tooltip.pink="`dasda`">teste</span>
      <span v-tooltip.primary="`dasda`">tesdsadaste</span>
      <span v-tooltip.blue="`dasda`">dsada</span>
      <span v-tooltip.red="`dasda`">fsdfsfsd</span>
      <span v-tooltip.indigo="`dasda`">aaa</span>
      <span v-tooltip="`aaaaaaassss`">zssszz</span>
    </div>
  </label>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import Tooltip from "../../Directives/Tooltip";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";

export default defineComponent({
  name: "InputLabel",
  props: {
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
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