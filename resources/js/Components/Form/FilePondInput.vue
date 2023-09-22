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
import {FilePondFile, FilePondOptions} from "filepond";

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
    FilePond.registerPlugin(...this.params.plugins ?? [])
    this.filePond = FilePond.create(this.$refs.input, this.getOptions());
    this.filePond.on('initfile', this.loading);
    this.filePond.on('processfilerevert', this.loading);
    this.filePond.on('processfiles', this.loaded);
    this.filePond.on('removefile', this.loaded);
    this.filePond.on('error', this.validationFail);
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
    getInstance(): FilePond {
      return this.filePond;
    },
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
    },
    loaded(): void {
      let serverIds: string[] = [];
      this.filePond.getFiles().forEach((file: FilePondFile) => {
        serverIds.push(file.serverId);
      });
      this.form[this.field] = this.params?.options?.allowMultiple ?? false ? serverIds : serverIds[0] ?? null;
      console.log(this.form[this.field]);
      this.form.processing = false;
    },
    loading() {
      this.form.processing = true;
    },
    validationFail() {
      this.form.processing = false;
    },
  }
})
</script>

<style scoped>

</style>