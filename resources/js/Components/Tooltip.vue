<template>
  <div :class="[show ? 'opacity-100' : 'opacity-0', 'bg-primary-700 z-50 transition-opacity ease-in-out duration-200']">
    <template v-if="!options.html">
      {{ options.title }}
    </template>
    <div v-else v-html="options.title"/>
  </div>
</template>

<script lang="ts">
import {arrow, autoPlacement, autoUpdate, computePosition, flip, offset, shift} from '@floating-ui/dom';
import {defineComponent, PropType} from "vue";
import {TooltipOptions} from "../Directives/Tooltip";

export default defineComponent({
  name: "Tooltip",
  props: {
    floating: Object as PropType<HTMLElement>,
    options: Object as PropType<TooltipOptions>,
    reference: Object as PropType<HTMLElement>,
  },
  data() {
    return {
      show: false,
      arrowLeft: 0,
      arrowTop: 0,
    }
  },
  computed: {
    finalOptions(): Object {
      let options : {
        placement: undefined|string,
        middleware: any[],
      } = {
      };
      options.middleware = [
        offset(10)
      ];
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
      return options;
    }
  },
  mounted() {
    autoUpdate(this.reference, this.floating, () => {
      computePosition(this.reference, this.floating, this.finalOptions)
          .then(({x, y, middlewareData}) => {
            this.floating.style.top = `${y}px`;
            this.floating.style.left = `${x}px`;
          })
    })
    this.reference.addEventListener('mouseenter', this.showTooltip);
    this.reference.addEventListener('mouseout', this.hideTooltip);
  },
  beforeUnmount() {
    this.reference.removeEventListener('mouseenter', this.showTooltip);
    this.reference.removeEventListener('mouseout', this.hideTooltip);
  },
  methods: {
    showTooltip() {
      console.log('hover')
      this.show = true;
    },
    hideTooltip() {
      console.log('end hover')
      this.show = false;
    }
  }
})
</script>

<style scoped>

</style>