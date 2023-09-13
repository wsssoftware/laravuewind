<template>
  <div :class="['lvw-input-feedback', {'lvw-without-feedback': !hasFeedback}]">
    <div v-if="hasFeedback" :class="['lvw-feedback', errors !== false ? 'lvw-red' :  `lvw-${theme}`]" @click="focusInput">
      {{ finalMessage }}
    </div>
    <MaxLength v-if="showMaxLength" :id="id" :field="field" :form="form" :show-max-length="showMaxLength" :theme="theme"/>
  </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import MaxLength from "./MaxLength.vue";

export default defineComponent({
  name: "InputFeedback",
  components: {MaxLength},
  props: {
    id: String,
    feedback: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    showMaxLength: Boolean,
    theme: String,
  },
  computed: {
    errors(): string|false
    {
      let errors: undefined|string|false = this.form.errors[this.field];
      return errors !== false && (errors === undefined || errors.trim() === '') ? false : errors;
    },
    finalMessage(): false|string {
      let feedback : string|undefined|false = this.feedback;
      return feedback === undefined || feedback.trim() === '' ? false : feedback;
    },
    hasFeedback(): boolean {
      return this.finalMessage !== false;
    },
  },
  methods: {
    focusInput() {
      document.getElementById(this.id)?.focus();
    },
  },
})
</script>

<style lang="scss" scoped>
.lvw-input-feedback {
  @apply mt-1 w-full flex justify-between items-start gap-2;
  &.lvw-without-feedback {
    @apply flex-row-reverse;
  }
  .lvw-feedback {
    @apply grow text-sm ps-1;
    &.lvw-gray {
      @apply text-gray-800/80
    }
    &.lvw-green {
      @apply text-green-900/80
    }
    &.lvw-indigo {
      @apply text-indigo-900/80
    }
    &.lvw-primary {
      @apply text-primary-900/80
    }
    &.lvw-slate {
      @apply text-slate-900/80
    }
    &.lvw-red {
      @apply text-red-700/80
    }
  }
}
</style>