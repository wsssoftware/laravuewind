<template>
  <MultipleSelected
      v-if="multiple && Array.isArray(choice)"
      @remove="$emit('remove', $event)"
      :choices="choice"
      :theme="theme"/>
  <span v-else class="truncate">{{ choice.value }}</span>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {SelectChoice} from "../InputTypes";
import MultipleSelected from "./MultipleSelected.vue";

export default defineComponent({
  name: "Selected",
  components: {MultipleSelected},
  props: {
    choice: {type: Object as PropType<SelectChoice|SelectChoice[]>, required: true},
    multiple: {type: Boolean, required: true},
    theme: {type: String, required: true},
  },
  emits: {
    remove(choice: SelectChoice): boolean {
      return true;
    }
  },
  beforeMount() {
    if (Array.isArray(this.choice) && !this.multiple) {
      throw new Error('Selected choice must be an object when multiple is false.');
    }
  }
})
</script>

<style scoped lang="scss">

</style>