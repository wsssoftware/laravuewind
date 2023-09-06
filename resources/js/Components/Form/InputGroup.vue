<template>
  <div>
    <InputLabel/>
    <div class="mt-2">
      <input type="email" name="email" id="email" class="form-input block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="you@example.com" aria-describedby="email-description" />
    </div>
    <InputHelp/>
  </div>
</template>

<script lang="ts">
import InputLabel from "./InputLabel.vue";
import InputHelp from "./InputHelp.vue";

import {defineComponent} from "vue";

export default defineComponent({
  name: "InputGroup",
  components: {
    InputHelp,
    InputLabel
  },
  props: {

  },
})
</script>

<style scoped>

</style>