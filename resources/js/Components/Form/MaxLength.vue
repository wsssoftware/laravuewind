<template>
  <div v-if="maxlength !== null && length !== null && showMaxLength" :class="['lvw-input-maxlength', `lvw-${theme}`]">
    {{ `${fLength}/${fMaxlength}` }}
  </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";

export default defineComponent({
  name: "MaxLength",
  props: {
    id: String,
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    showMaxLength: Boolean,
    theme: String,
  },
  data() {
    return {
      el: null,
      maxlength: null,
      length: null,
    }
  },
  computed: {
    fLength(): string {
      return Intl.NumberFormat().format(this.length);
    },
    fMaxlength(): string {
      return Intl.NumberFormat().format(this.maxlength);
    },
    limit(): number|null {
      if (this.maxlength === null) {
        return null;
      }
      let constant = (1 -  (this.maxlength > 1000 ? 100 : this.maxlength / 10) / 100) / 2;
      let limit = Math.floor(this.maxlength * constant);
      let halfMaxlength = this.maxlength / 2;
      if (limit > halfMaxlength) {
        limit = Math.floor(this.maxlength / 2);
      }
      if (this.maxlength > 250) {
        limit = 30;
      }
      return limit;
    },
    // attentionLimit(): number|null {
    //   return this.length >= this.maxlength;
    // },
    // warnLimit(): number|null {
    //   return this.length >= this.maxlength;
    // },
  },
  mounted() {
    this.init();
    this.getLength(this.form[this.field]);
    this.$watch('form.' + this.field, this.getLength);
    console.log(this.limit);
  },
  methods: {
    getLength(word): null | number {
      this.length = word?.length ?? 0;
    },
    getMaxlength(): null | number {
      let maxlength: string | null = this?.el.getAttribute('maxlength');
      return typeof maxlength === 'string' && /^\d+$/.test(maxlength) ? parseInt(maxlength) : null;
    },
    init(): void {
      this.el = document.getElementById(this.id);
      this.maxlength = this.getMaxlength();
    }
  },
});
</script>

<style lang="scss" scoped>
.lvw-input-maxlength {
  @apply inline-flex items-center rounded-lg mt-[0.15em] px-[0.3em] py-[0.05em] text-[0.7em] font-medium ;
  &.lvw-gray {
    @apply  bg-gray-600/30 text-gray-700
  }

  &.lvw-green {
    @apply bg-green-600/30 text-green-700
  }

  &.lvw-indigo {
    @apply bg-indigo-600/30 text-indigo-700
  }

  &.lvw-primary {
    @apply bg-primary-600/30 text-primary-700
  }

  &.lvw-slate {
    @apply bg-slate-600/30 text-slate-700
  }

  &.lvw-red {
    @apply bg-red-600/30 text-red-700
  }

  &.lvw-yellow {
    @apply bg-yellow-600/30 text-yellow-700
  }
}
</style>