<template>
  <ListboxButton :class="[
      'lvw-input', {'lvw-open': open},
      hasError ? 'lvw-red' : `lvw-${theme}`
      ]">
    <span v-if="placeholder && choice === undefined" class="lvw-placeholder">{{ placeholder }}</span>
    <span v-else class="truncate">{{ choice }}</span>
    <div class="flex justify-center items-center gap-x-2">
      <button v-if="clearable && choice !== undefined" type="button" @click.prevent="clear">
        <XmarkLarge class="lvw-xmark-icon" aria-hidden="true"/>
      </button>
      <AnglesUpDown class="lvw-angles-icon" aria-hidden="true"/>
    </div>
  </ListboxButton>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {ListboxButton} from '@headlessui/vue'
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import AnglesUpDown from "../../Icons/AnglesUpDown.vue";
import {SelectChoice} from "../InputTypes";
import XmarkLarge from "../../Icons/XmarkLarge.vue";

export default defineComponent({
  name: "SelectButton",
  components: {
    XmarkLarge,
    AnglesUpDown,
    ListboxButton,
  },
  props: {
    clearable: Boolean,
    choices: {type: Object as PropType<SelectChoice[]>, required: true},
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    open: Boolean,
    placeholder: String,
    theme: String,
  },
  computed: {
    choice(): any {
      return this.choices.find((choice: SelectChoice) => choice.key === this.form[this.field])?.value;
    },
    hasError(): boolean {
      return !!this.form.errors[this.field];
    }
  },
  methods: {
    clear() {
      this.form[this.field] = '';
    }
  }
});
</script>

<style lang="scss" scoped>
.lvw-input {
  @apply form-input block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset sm:text-sm sm:leading-6;
  @apply min-h-[2.572em] flex justify-between items-center gap-x-1  items-center cursor-default;
  &.lvw-open {
    @apply ring-2;
  }
  .lvw-xmark-icon {
    @apply h-5 w-5 p-1 fill-red-500 transition-all duration-200 hover:fill-red-700 hover:p-[0.20rem];
  }

  .lvw-angles-icon {
    @apply h-3 w-3;
  }
  &.lvw-gray {
    @apply text-gray-800 ring-gray-500 focus:ring-gray-600;
    .lvw-placeholder {
      @apply text-gray-800/60;
    }
    .lvw-angles-icon {
      @apply fill-gray-800/75;
    }
  }

  &.lvw-green {
    @apply text-green-900 ring-green-500 focus:ring-green-600;
    .lvw-placeholder {
      @apply text-green-800/60;
    }
    .lvw-angles-icon {
      @apply fill-green-800/75;
    }
  }

  &.lvw-indigo {
    @apply text-indigo-900 ring-indigo-500 focus:ring-indigo-600;
    .lvw-placeholder {
      @apply text-indigo-800/60;
    }
    .lvw-angles-icon {
      @apply fill-indigo-800/75;
    }
  }

  &.lvw-primary {
    @apply text-primary-900 ring-primary-500 focus:ring-primary-600;
    .lvw-placeholder {
      @apply text-primary-800/60;
    }
    .lvw-angles-icon {
      @apply fill-primary-800/75;
    }
  }

  &.lvw-slate {
    @apply text-slate-900 ring-slate-500 focus:ring-slate-600;
    .lvw-placeholder {
      @apply text-slate-800/60;
    }
    .lvw-angles-icon {
      @apply fill-slate-800/75;
    }
  }

  &.lvw-red {
    @apply text-red-700 ring-red-700 focus:ring-red-700;
    .lvw-placeholder {
      @apply text-red-800/60;
    }
    .lvw-angles-icon {
      @apply fill-red-800/75;
    }
  }
}
</style>