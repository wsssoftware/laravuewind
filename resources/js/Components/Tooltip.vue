<template>
  <div :class="[
      hidden ? 'hidden' : '',
      show ? 'opacity-100' : 'opacity-0',
      options.theme === 'primary' ? 'bg-primary-800/90' : '',
      options.theme === 'slate' ? 'bg-slate-800/90' : '',
      options.theme === 'red' ? 'bg-red-800/90' : '',
      options.theme === 'yellow' ? 'bg-yellow-800/90' : '',
      options.theme === 'green' ? 'bg-green-800/90' : '',
      options.theme === 'blue' ? 'bg-blue-800/90' : '',
      options.theme === 'indigo' ? 'bg-indigo-800/90' : '',
      options.theme === 'pink' ? 'bg-pink-800/90' : '',
      'z-50 text-center text-white rounded shadow-sm transition-opacity ease-in-out duration-400'
      ]">
    <div class="px-2 py-1" v-if="!options.html">
      {{ options.title }}
    </div>
    <div class="px-2 py-1" v-else v-html="options.title"/>
    <div v-if="options.arrow" ref="arrow" class="absolute w-[10px] h-[10px]">
      <svg height="10" width="10">
        <polygon points="0,5 10,5 5,10" :class="[
          options.theme === 'primary' ? 'fill-primary-800/90' : '',
          options.theme === 'slate' ? 'fill-slate-800/90' : '',
          options.theme === 'red' ? 'fill-red-800/90' : '',
          options.theme === 'yellow' ? 'fill-yellow-800/90' : '',
          options.theme === 'green' ? 'fill-green-800/90' : '',
          options.theme === 'blue' ? 'fill-blue-800/90' : '',
          options.theme === 'indigo' ? 'fill-indigo-800/90' : '',
          options.theme === 'pink' ? 'fill-pink-800/90' : '',
        ]" />
      </svg>
    </div>
  </div>
</template>

<script lang="ts">
import {arrow, autoPlacement, autoUpdate, computePosition, flip, offset, shift} from '@floating-ui/dom';
import {defineComponent, PropType} from "vue";
import {TooltipOptions, calculateArrowPosition} from "../Directives/Tooltip";

export default defineComponent({
  name: "Tooltip",
  props: {
    floating: Object as PropType<HTMLElement>,
    options: Object as PropType<TooltipOptions>,
    reference: Object as PropType<HTMLElement>,
  },
  data() {
    return {show: false, hidden: true, hiddenTimeout:null}
  },
  computed: {
    finalOptions(): Object {
      let options : { placement: undefined|string, middleware: any[], } = {placement: undefined, middleware: [offset(7)]};
      if (this.options.placement === 'auto') {
        options.middleware.push(autoPlacement())
      } else {
        options.placement = this.options.placement;
      }
      if (this.options.flip) {
        options.middleware.push(flip())
      }
      if (this.options.shift) {
        options.middleware.push(shift())
      }
      if (this.options.arrow) {
        options.middleware.push(arrow({element: this.$refs.arrow}));
      }
      return options;
    }
  },
  beforeMount() {
    this.reference.addEventListener('click', this.mouseClick);
    this.reference.addEventListener('mouseover', this.showTooltip);
    this.reference.addEventListener('mouseout', this.hideTooltip);
  },
  mounted(): void {
    this.init();
  },
  beforeUnmount(): void {
    this.reference.removeEventListener('click', this.mouseClick);
    this.reference.removeEventListener('mouseenter', this.showTooltip);
    this.reference.removeEventListener('mouseout', this.hideTooltip);
  },
  methods: {
    init() : void {
      autoUpdate(this.reference, this.floating, () => {
        computePosition(this.reference, this.floating, this.finalOptions)
            .then(({x, y, middlewareData, placement}) => {
              if (middlewareData.arrow && this.$refs.arrow) {
                const {x, y} = middlewareData.arrow;
                calculateArrowPosition(x, y, placement, this.$refs.arrow)
              }
              this.floating.style.top = `${y}px`;
              this.floating.style.left = `${x}px`;
            })
      })
    },
    mouseClick(): void {
      this.show ? this.hideTooltip() : this.showTooltip();
    },
    showTooltip() : void {
      clearTimeout(this.hiddenTimeout);
      this.hidden = false;
      this.show = true;
    },
    hideTooltip() : void {
      this.show = false;
      this.hiddenTimeout = setTimeout(() => {
        this.hidden = true;
      }, 500);
    }
  }
})
</script>