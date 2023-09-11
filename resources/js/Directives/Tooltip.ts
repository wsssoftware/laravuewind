import {App, createApp, DefineComponent, defineComponent, DirectiveBinding} from "vue";
import Tooltip from '../Components/Tooltip.vue';

export type Placement =
    | 'auto'
    | 'top'
    | 'top-start'
    | 'top-end'
    | 'right'
    | 'right-start'
    | 'right-end'
    | 'bottom'
    | 'bottom-start'
    | 'bottom-end'
    | 'left'
    | 'left-start'
    | 'left-end';

export type TooltipOptions = {
    arrow: boolean,
    flip: boolean,
    html: boolean,
    placement: Placement | string,
    shift: boolean,
    title: string,
};


const factory = new class {
    private readonly id: string;

    private floating: HTMLElement;

    private floatingApp: App<Element>

    private readonly component: DefineComponent;

    constructor() {
        this.id = 'tooltip-' + Math.random().toString(16).slice(2);
        this.component = defineComponent({
            extends: Tooltip,
        });
    }

    public createFloatingApp(el: HTMLElement, binding: DirectiveBinding): void {

        this.floatingApp = createApp(this.component, {
            floating: this.floating,
            options: this.parseOptions(binding),
            reference: el,
            title: 'abc',
        });

        this.floatingApp.mount(this.floating);
    }

    public createFloatingDiv(): void {
        this.floating = document.createElement('div');
        this.floating.id = this.id;
        this.floating.style.position = 'absolute';
        this.floating.style.width = 'max-content';
        this.floating.style.top = '0';
        this.floating.style.left = '0';
        document.body.appendChild(this.floating);
    }

    public destroyFloatingDiv(): void {
        if (this.floatingApp && this.floatingApp._container) {
            this.floatingApp.unmount();
        }
        if (this.floating) {
            document.body.removeChild(this.floating);
        }
    }

    protected parseOptions(binding: DirectiveBinding): TooltipOptions {
        let title = binding.value;
        if (typeof title !== 'string' || title.trim() === '') {
            throw new Error('Tooltip directive value must have a title property and cannot be empty');
        }
        return {
            arrow: !(binding.modifiers.withoutArrow ?? false),
            flip: !(binding.modifiers.withoutFlip ?? false),
            html: binding.modifiers.html ?? false,
            placement: binding.arg || 'auto',
            shift: !(binding.modifiers.withoutShift ?? false),
            title: title.trim(),
        };
    }

    public reloadFloatingApp(el: HTMLElement, binding: DirectiveBinding): void {
        if (this.floatingApp && this.floatingApp._container) {
            this.floatingApp.unmount();
        }
        this.createFloatingApp(el, binding)
    }
}


const tooltip = {
    mounted(el: HTMLElement, binding: DirectiveBinding) {
        factory.createFloatingApp(el, binding);
    },
    beforeMount(): void {
        factory.createFloatingDiv();
    },
    updated(el: HTMLElement, binding: DirectiveBinding): void {
        factory.reloadFloatingApp(el, binding)
    },
    beforeUnmount(): void {
        factory.destroyFloatingDiv();
    },
}

export default tooltip;