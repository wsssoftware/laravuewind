<template>
  <div class="flex justify-start items-center gap-1 flex-wrap">
    <MultiSelectedItem
        v-for="choice in choices"
        :key="choice.value"
        @remove="$emit('remove', choice)"
        :choice="choice"
        :theme="theme"/>
  </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {SelectChoice} from "../InputTypes";
import XmarkLarge from "../../Icons/XmarkLarge.vue";
import Tooltip from "../../../Directives/Tooltip";
import MultiSelectedItem from "./MultiSelectedItem.vue";

export default defineComponent({
  name: "MultipleSelected",
  components: {MultiSelectedItem},
  props: {
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    theme: {type: String, required: true},
  },
  emits: {
    remove(choice: SelectChoice): boolean {
      return true;
    }
  },
})
</script>