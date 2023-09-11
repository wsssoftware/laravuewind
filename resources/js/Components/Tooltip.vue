<template>
  <span class="lvw-tooltip bg-primary-700 z-50">
    dsadas
  </span>
</template>

<script lang="ts">
import {computePosition, autoUpdate} from '@floating-ui/dom';
export default {
  name: "Tooltip",
  props: {
    title: String,
  },
  methods: {
    init(reference: HTMLElement, tooltip: HTMLElement) {
      autoUpdate(reference, tooltip, () => {
          computePosition(reference, tooltip, {
              placement: 'top',
          }).then(({x, y}) => {
            tooltip.style.top = `${y}px`;
            tooltip.style.left = `${x}px`;
          })
      })
    }
  }
}
</script>

<style scoped>
.lvw-tooltip {
  position: absolute;
  width: max-content;
  top: 0;
  left: 0;
}
</style>