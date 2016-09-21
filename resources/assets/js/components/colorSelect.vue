<template>
  <div class="colorSelect"
       :style="{ background: color }"
       @click="selectColor"></div>
</template>

<script>
export default {
  props: {
    color: String
  },

  methods: {
    selectColor(){ this.$dispatch('selectColor', this.color) }
  }
}
</script>

<style scoped>
.colorSelect{
  display: inline-block;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  margin-right: 10px;
  cursor: pointer;
}

.colorSelect:hover{
  transform: scale(1.1);
}
</style>
