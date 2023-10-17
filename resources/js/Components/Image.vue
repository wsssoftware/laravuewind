<template>
    <div :style="[{'aspect-ratio': dimensions ? `${dimensionsX}/${dimensionsY}` : null}]">
        <img
            v-bind="$attrs"
            ref="img"
            :alt="alt"
            class="w-full"
            :data-src="src"
            :src="loadingSrc ?? 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NgAAIAAAUAAR4f7BQAAAAASUVORK5CYII='"/>
    </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import LazyLoad, {ILazyLoadInstance} from "vanilla-lazyload";

export default defineComponent({
    inheritAttrs: false,
    name: "Image",
    props: {
        alt: String,
        dimensions: Array as PropType<[x: number, y: number]>,
        errorSrc: String,
        loadingSrc: String,
        src: {
            type: String,
            required: true,
        },
    },
    emits: ['enter', 'exit', 'applied', 'loading', 'loaded', 'error', 'finish', 'cancel'],
    data() {
        return {
            lazyLoad: null,
            dimensionsX: null,
            dimensionsY: null,
        }
    },
    computed: {
        options() {
            return {
                callback_enter: (el: HTMLElement, entry: IntersectionObserverEntry, instance: ILazyLoadInstance) => this.$emit('enter', el, entry, instance),
                callback_exit: (el: HTMLElement, entry: IntersectionObserverEntry, instance: ILazyLoadInstance) => this.$emit('exit', el, entry, instance),
                callback_applied: (el: HTMLElement, instance: ILazyLoadInstance) => this.$emit('applied', el, instance),
                callback_loading: (el: HTMLElement, instance: ILazyLoadInstance) => this.$emit('loading', el, instance),
                callback_loaded: this.onLoaded,
                callback_error: this.onError,
                callback_finish: (instance: ILazyLoadInstance) => this.$emit('finish', instance),
                callback_cancel: (el: HTMLElement, entry: IntersectionObserverEntry, instance: ILazyLoadInstance) => this.$emit('cancel', el, entry, instance),
                class_applied: 'lvw-ll-applied',
                class_entered: 'lvw-ll-entered',
                class_error: 'lvw-ll-error',
                class_exited: 'lvw-ll-exited',
                class_loaded: 'lvw-ll-loaded',
                class_loading: 'lvw-ll-loading',
            }
        }
    },
    mounted() {
        this.create();
    },
    methods: {
        create() {
            this.dimensionsX = this.dimensions[0];
            this.dimensionsY = this.dimensions[1];
            this.lazyLoad = new LazyLoad(this.options, [this.$refs.img]);
        },
        destroy() {
            if (this.lazyLoad) {
                this.lazyLoad.destroy();
            }
            LazyLoad.resetStatus(this.$refs.img);
        },
        onError(el: HTMLElement, instance: ILazyLoadInstance) {
            this.$emit('error', el, instance);
            el.setAttribute("src", this.errorSrc);
        },
        onLoaded(el: HTMLImageElement, instance: ILazyLoadInstance) {
            this.$emit('loaded', el, instance);
            setTimeout(() => {
                this.$nextTick(() => {
                    this.dimensionsX = el.width;
                    this.dimensionsY = el.height;
                });
            }, 300);
        },
    },
    watch: {
        src() {
            this.destroy();
            this.create();
        }
    }

});
</script>

<style scoped>

</style>
