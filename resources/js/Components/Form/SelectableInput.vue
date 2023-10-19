<template>
    <Listbox as="div" class="relative" :disabled="disabled" v-model="form[field]" v-slot="{open}" :multiple="multiple">
        <SelectButton
            :clearable="clearable"
            :choices="finalChoices"
            :disabled="disabled"
            :field="field"
            :form="form"
            :loading="loading"
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
        choices: {type: [Object, String, null], required: true},
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
            loading: false,
            finalChoices: [],
        }
    },
    beforeMount() {
        if (Array.isArray(this.form[this.field])) {
            this.form[this.field] = this.form[this.field].map((value) => String(value));
        } else if (this.form[this.field]) {
            this.form[this.field] = String(this.form[this.field]);
        }
        this.fetchChoices();
    },
    methods: {
        fetchChoices() {
            if (Array.isArray(this.choices)) {
                this.finalChoices = this.choices;
                return;
            }
            if (this.choices === null) {
                this.finalChoices = [];
                return;
            }
            if (typeof this.choices === 'string') {
                this.loading = true;
                axios.get(this.choices).then((response) => {
                    this.finalChoices = response.data;
                    if (Array.isArray(this.form[this.field])) {
                        this.form[this.field] = this.form[this.field].filter((value) => {
                            return this.finalChoices.find((choice) => {
                                return choice.key === value;
                            });
                        });
                    } else if(this.form[this.field]) {
                        let foundItems = this.finalChoices.find((choice) => {
                            return choice.key === this.form[this.field];
                        });
                        if (!foundItems) {
                            this.form[this.field] = null;
                        }
                    }
                    this.loading = false;
                }).catch((error) => {
                    console.warn(error);
                    this.finalChoices = [];
                    this.loading = false;
                });
            }
        }
    },
    watch: {
        choices() {
            this.fetchChoices();
        }
    }
});
</script>

<style scoped>

</style>
