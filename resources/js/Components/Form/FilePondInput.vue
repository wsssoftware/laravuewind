<template>
  <input ref="input" type="file">
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {PropType} from "vue/dist/vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import {FilePondParams} from "./InputTypes";

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
    return {}
  },
  computed: {},
  methods: {}
})
</script>

<style scoped>

</style>