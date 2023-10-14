<template>
    <Listbox as="div" class="relative" :disabled="disabled" v-model="form[field]" v-slot="{open}" :multiple="multiple">
        <SelectButton
            :clearable="clearable"
            :choices="finalChoices"
            :disabled="disabled"
            :field="field"
            :form="form"
            :multiple="multiple"
            :open="open"
            :placeholder="placeholder"
            :theme="theme"/>

        <Options
            :choices="finalChoices"
            :component="component"
            :open="open"
            :searchable="searchable"
            :theme="theme"/>
    </Listbox>
</template>

<script lang="ts">
import {Component, defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import {SelectChoice} from "./InputTypes";
import {Listbox} from '@headlessui/vue'
import SelectButton from "./SelectableInput/SelectButton.vue";
import Options from "./SelectableInput/Options.vue";

export default defineComponent({
    name: "SelectableInput",
    components: {
        Options,
        SelectButton,
        Listbox,
    },
    props: {
        clearable: {type: Boolean, required: true},
        choices: Object as PropType<SelectChoice[]>,
        choicesUrl: String,
        component: Object as PropType<Component>,
        disabled: Boolean,
        id: {type: String, required: true},
        field: {type: String, required: true},
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
        multiple: {type: Boolean, required: true},
        placeholder: String,
        searchable: {type: Boolean, required: true},
        theme: {type: String, required: true},
    },
    data() {
        return {
            finalChoices: [],
        }
    },
    beforeMount() {
        if (Array.isArray(this.form[this.field])) {
            this.form[this.field] = this.form[this.field].map((value) => String(value));
        } else {
            this.form[this.field] = String(this.form[this.field]);
        }
        if (this.choicesUrl !== undefined) {
            this.finalChoices = [];
            this.fetchChoices();
        } else if (this.choices !== undefined) {
            this.finalChoices = this.choices;
        }
    },
    methods: {
        fetchChoices() {
            if (this.choicesUrl) {
                axios.get(this.choicesUrl).then((response) => {
                    this.finalChoices = response.data;
                });
            }
        }
    },
});
</script>

<style scoped>

</style>
