
import {createApp, defineComponent, h} from "vue";
import Tooltip from '../Components/Tooltip.vue';

const id = 'tooltip-' + Math.random().toString(16).slice(2);

function createTooltipDiv(): void {
    const div = document.createElement('div');
    div.id = id;
    div.style.width = '100%';
    div.style.height = '100%';
    div.style.position = 'fixed';
    div.style.top = '0';
    div.style.left = '0';
    document.body.appendChild(div);
}
function destroyTooltipDiv(): void {
    const div = document.getElementById(id);
    if (div) {
        document.body.removeChild(div);
    }
}

const tooltip = {
    // called before bound element's attributes
    // or event listeners are applied
    created(el, binding, vnode, prevVnode) {
        // see below for details on arguments
    },
    // called right before the element is inserted into the DOM.
    mounted(el: HTMLElement, binding, vnode, prevVnode) {
        // el.addEventListener('mouseenter', function(e) {
        //     console.log('hover')
        // });
        // el.addEventListener('mouseout', function(e) {
        //     console.log('end hover')
        // });

        let tooltip = defineComponent({
            extends: Tooltip,
        });

        let div = document.getElementById(id);

        const abc = createApp(tooltip).mount(div)

        abc.init(el, abc.$el);

        // console.log(abc.$el);

        // let node = document.createElement('span');
        // node.innerHTML = 'dsadas';
        // node.id = 'tooltip';
        // node.style.position = 'absolute';
        // node.style.width = 'max-content';
        // binding.instance.$root.$el.appendChild(node);

        // autoUpdate(el, node, () => {
        //     computePosition(el, node, {
        //         placement: 'top',
        //     }).then(({x, y}) => {
        //         node.style.top = `${y}px`;
        //         node.style.left = `${x}px`;
        //     })
        // })


    },
    // called when the bound element's parent component
    // and all its children are mounted.
    beforeMount(el: HTMLElement, binding, vnode, prevVnode) {
        createTooltipDiv();
    },
    // called before the parent component is updated
    beforeUpdate(el, binding, vnode, prevVnode) {},
    // called after the parent component and
    // all of its children have updated
    updated(el, binding, vnode, prevVnode) {},
    // called before the parent component is unmounted
    beforeUnmount(el: HTMLElement, binding, vnode, prevVnode) {
        destroyTooltipDiv();
    },
    // called when the parent component is unmounted
    unmounted(el, binding, vnode, prevVnode) {}
}

export default tooltip;