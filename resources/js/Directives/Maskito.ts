import {DirectiveBinding, VNode} from "vue";
import {Maskito, MaskitoOptions} from "@maskito/core";

const instances: Map<HTMLInputElement | HTMLTextAreaElement, Maskito> = new Map();


function mountMaskito(el: HTMLInputElement | HTMLTextAreaElement, options: MaskitoOptions): void {
    let maskito = new Maskito(el, options);
    instances.set(el, maskito);
}

export default new class {
    // called when the bound element's parent component
    // and all its children are mounted.
    mounted(el: HTMLInputElement | HTMLTextAreaElement, binding: DirectiveBinding, vnode: VNode, prevVnode: VNode) {
        if (typeof binding.value === 'object') {
            if (el.type !== 'text' && el.type !== 'textarea') {
                console.warn('Maskito directive only works with text and textarea elements');
                return;
            }
            mountMaskito(el, binding.value);
        }
    }

    // called before the parent component is unmounted
    beforeUnmount(el: HTMLInputElement | HTMLTextAreaElement, binding: DirectiveBinding, vnode: VNode, prevVnode: VNode) {
        instances.get(el)?.destroy();
    }
}