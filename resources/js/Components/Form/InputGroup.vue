<template>
    <div v-bind="parentAttrs">
        <slot v-if="labelSlotExists" :id="finalId" :field="field" :form="form" :help="help" :label="label"
              :required="required" :theme="theme" name="label"/>
        <InputLabel v-else-if="!!label" :id="finalId" :field="field" :form="form" :help="help" :required="required"
                    :theme="theme">{{ label }}
        </InputLabel>
        <div class="mt-1">
            <slot
                v-if="inputSlotExists"
                :id="finalId"
                :field="field"
                :filePond="filePond"
                :form="form"
                :inputmode="inputmode"
                :maskito="maskito"
                :required="required"
                :theme="theme"
                :type="type"
                name="input"/>
            <SelectableInput
                v-else-if="isSelectable"
                v-bind="$attrs"
                :clearable="select.clearable ?? false"
                :choices="select.choices"
                :component="select.optionComponent"
                :id="finalId"
                :field="field"
                :form="form"
                :multiple="select.multiple ?? false"
                :searchable="select.searchable ?? false"
                :theme="theme"/>
            <FillableInput
                ref="input"
                v-else-if="isFillable"
                v-bind="$attrs"
                :id="finalId"
                :field="field"
                :form="form"
                :inputmode="inputmode"
                :maskito="maskito"
                :required="required"
                :theme="theme"
                :type="type"/>
            <FilePondInput
                v-else-if="isFilePond"
                v-bind="$attrs"
                :id="finalId"
                :field="field"
                :form="form"
                :params="filePond"
                :required="required"
                :theme="theme"/>
        </div>
        <slot
            v-if="feedbackSlotExists"
            :id="finalId"
            :field="field"
            :form="form"
            :hasArrayErrors="isFilePond"
            :showMaxLength="showMaxLength"
            :theme="theme"
            name="feedback"/>
        <InputFeedback
            v-else
            :id="finalId"
            :feedback="feedback"
            :field="field"
            :form="form"
            :has-array-errors="isFilePond"
            :show-max-length="showMaxLength"
            :theme="theme"/>
    </div>
</template>

<script lang="ts">
import FillableInput from "./FillableInput.vue";
import InputLabel from "./InputLabel.vue";
import InputFeedback from "./InputFeedback.vue";
import type {PropType} from 'vue'
import {defineComponent} from "vue";
import {InertiaForm} from "@inertiajs/vue3/types/useForm";
import InputTypes, {FilePondParams, Fillable, SelectOptions} from "./InputTypes";
import {MaskitoOptions} from "@maskito/core";
import SelectableInput from "./SelectableInput.vue";
import FilePondInput from "./FilePondInput.vue";

type sa = string;

export default defineComponent({
    inheritAttrs: false,
    name: "InputGroup",
    components: {
        FilePondInput,
        SelectableInput,
        FillableInput,
        InputFeedback,
        InputLabel
    },
    props: {
        id: String,
        feedback: String,
        field: {type: String, required: true},
        filePond: Object as PropType<FilePondParams>,
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
        help: String,
        ignoreFieldCheck: {
            type: Boolean,
            default: false,
        },
        inputmode: String as PropType<'none' | 'text' | 'decimal' | 'numeric' | 'tel' | 'search' | 'email' | 'url'>,
        label: String,
        maskito: Object as PropType<MaskitoOptions>,
        parentAttrs: Object,
        required: Boolean,
        select: Object as PropType<SelectOptions>,
        showMaxLength: {
            type: Boolean,
            default: true,
        },
        theme: {
            type: String,
            default: 'primary',
            validator(value: string): boolean {
                return ['gray', 'green', 'indigo', 'primary', 'slate'].includes(value);
            }
        },
        type: {
            type: String,
            default: 'text',
            validator(value: string): boolean {
                return InputTypes.includes(value);
            }
        },
    },
    computed: {
        finalId(): string {
            return this.id || 'input-' + Math.random().toString(16).slice(2);
        },
        feedbackSlotExists(): boolean {
            return !!this.$slots.feedback;
        },
        inputSlotExists(): boolean {
            return !!this.$slots.input;
        },
        isFilePond(): boolean {
            return this.type === 'filepond'
        },
        isFillable(): boolean {
            return Fillable.includes(this.type);
        },
        isSelectable(): boolean {
            return this.select && (Array.isArray(this.select.choices) || typeof this.select.choices === 'string' || this.select.choices === null);
        },
        labelSlotExists(): boolean {
            return !!this.$slots.label;
        },
    },
    beforeMount() {
        if (this.form[this.field] === undefined && this.ignoreFieldCheck === false) {
            throw new Error(`The form field "${this.field}" is missing from the component's form.`);
        }
    },
    methods: {
        input() {
            if (this.$refs.input) {
                return this.$refs.input.$el;
            }
        },
    }
})
</script>

<style scoped>

</style>
