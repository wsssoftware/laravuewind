<template>
  <div>
    <input ref="input" type="file">
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {PropType} from "vue/dist/vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import {FilePondParams} from "./InputTypes";
import * as FilePond from "filepond";
import {FilePondOptions} from "filepond";

export default defineComponent({
  name: "FilePondInput",
  props: {
    id: {type: String, required: true},
    field: {type: String, required: true},
    form: {type: Object as PropType<InertiaForm<object>>, required: true},
    params: Object as PropType<FilePondParams>,
    theme: {type: String, required: true},
  },
  emits: [],
  data() {
    return {
      filePond: null,
    }
  },
  mounted() {
    this.filePond = FilePond.create(this.$refs.input, this.getOptions());
  },
  updated() {
    if (this.filePond) {
      this.filePond.setOptions(this.getOptions());
    }
  },
  beforeUnmount() {
    if (this.filePond) {
      this.filePond.destroy();
    }
  },
  methods: {
    getOptions(): FilePondOptions {
      return {
        ...this?.$lvw?.languageStrings?.filePond ?? {},
        ...this.getServer(),
        ...this.params?.options ?? {},
      }
    },
    getServer(): object {
      return {
        server: {
          url: route('lvw.filepond'),
          process: '/process',
          revert: '/process',
          patch: "?patch=",
          headers: {
            'X-CSRF-TOKEN': this.$page.props.csrf_token,
          }
        }
      };
    }
  }
})
</script>

<style scoped>

</style>