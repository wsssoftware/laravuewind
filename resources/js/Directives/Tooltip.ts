import {App, createApp, defineComponent, DirectiveBinding} from "vue";
import Tooltip from '../Components/Tooltip.vue';

export type Themes =
    | 'primary'
    | 'slate'
    | 'red'
    | 'yellow'
    | 'green'
    | 'blue'
    | 'indigo'
    | 'pink'
    |  string;

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
    theme: Themes,
    title: string,
};

export function calculateArrowPosition(x: number|null, y: number|null, placement: Placement, arrow: HTMLElement) {
    arrow.style.left = '';
    arrow.style.top = '';
    arrow.style.bottom = '';
    arrow.style.right = '';
    arrow.style.transform = 'rotate(0deg)';
    if (placement.includes('bottom')) {
        arrow.style.left = x ? `${x}px` : '';
        arrow.style.top = `${-arrow.offsetWidth / 2}px`;
        arrow.style.transform = 'rotate(180deg)';
    }
    if (placement.includes('top')) {
        arrow.style.left = x ? `${x}px` : '';
        arrow.style.bottom = `${-arrow.offsetWidth / 2}px`;
    }
    if (placement.includes('left')) {
        arrow.style.top = y ? `${y}px` : '';
        arrow.style.right = `${-arrow.offsetWidth / 2}px`;
        arrow.style.transform = 'rotate(270deg)';
    }
    if (placement.includes('right')) {
        arrow.style.top = y ? `${y}px` : '';
        arrow.style.left = `${-arrow.offsetWidth / 2}px`;
        arrow.style.transform = 'rotate(90deg)';
    }
}

const component = defineComponent({extends: Tooltip});

const instances: Factory[] = [];

class Factory {
    private readonly id: string;

    private readonly reference: HTMLElement;

    private floating: HTMLElement;

    private floatingApp: App<Element>

    constructor(reference: HTMLElement) {
        this.reference = reference;
        this.id = 'tooltip-' + Math.random().toString(16).slice(2);
    }

    public createFloatingApp(el: HTMLElement, binding: DirectiveBinding): void {

        this.floatingApp = createApp(component, {
            floating: this.floating,
            options: this.parseOptions(binding),
            reference: el,
            title: 'abc',
        });

        this.floatingApp.mount(this.floating);
    }

    public createFloatingDiv(el: HTMLElement): void {
        let parent = el.parentElement;
        this.floating = document.createElement('div');
        this.floating.id = this.id;
        this.floating.style.position = 'absolute';
        this.floating.style.width = 'max-content';
        this.floating.style.top = '0';
        this.floating.style.left = '0';
        parent.insertBefore(this.floating, el.nextSibling);
    }

    public destroyFloating(): void {
        if (this.floatingApp && this.floatingApp._container) {
           this.floatingApp.unmount();
        }
        instances.splice(instances.indexOf(this), 1);
    }

    public isThisReference(el: HTMLElement): boolean {
        return this.reference === el;
    }

    protected parseOptions(binding: DirectiveBinding): TooltipOptions {
        let title = binding.value;
        if (typeof title !== 'string' || title.trim() === '') {
            throw new Error('Tooltip directive value must have a title property and cannot be empty');
        }
        let theme = 'slate';
        theme = binding.modifiers.primary ? 'primary' : theme;
        theme = binding.modifiers.slate ? 'slate' : theme;
        theme = binding.modifiers.red ? 'red' : theme;
        theme = binding.modifiers.yellow ? 'yellow' : theme;
        theme = binding.modifiers.green ? 'green' : theme;
        theme = binding.modifiers.blue ? 'blue' : theme;
        theme = binding.modifiers.indigo ? 'indigo' : theme;
        theme = binding.modifiers.pink ? 'pink' : theme;
        return {
            arrow: !(binding.modifiers.withoutArrow ?? false),
            flip: !(binding.modifiers.withoutFlip ?? false),
            html: binding.modifiers.html ?? false,
            placement: binding.arg || 'auto',
            shift: !(binding.modifiers.withoutShift ?? false),
            theme: theme,
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
function getFactory(el: HTMLElement): Factory
{
    let filtered = instances.filter((instance) => instance.isThisReference(el));
    if (filtered.length === 0) {
        let factory = new Factory(el);
        instances.push(factory);
        return factory;
    }
    return filtered[0];
}

const tooltip = {
    mounted(el: HTMLElement, binding: DirectiveBinding) {
        if (binding.value === false) {
            return;
        }
        let factory = getFactory(el);
        factory.createFloatingDiv(el);
        factory.createFloatingApp(el, binding);
    },
    updated(el: HTMLElement, binding: DirectiveBinding): void {
        if (binding.value === false) {
            return;
        }
        getFactory(el).reloadFloatingApp(el, binding)
    },
    beforeUnmount(el: HTMLElement, binding: DirectiveBinding): void {
        if (binding.value === false) {
            return;
        }
        getFactory(el).destroyFloating();
    }
}

export default tooltip;