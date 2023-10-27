<template>
    <button class="disabled:opacity-75" :disabled="form?.processing">
        <slot/>
    </button>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";

export default defineComponent({
    name: "FormButton",
    props: {
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
    }
});
</script>

<style scoped>

</style>
