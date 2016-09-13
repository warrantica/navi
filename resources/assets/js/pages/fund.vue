<template>
  <div class="routerWrapper">
    <div class="section">
      This is {{ fundData.name }} <br>
      Nav: {{ fundData.nav }}
    </div>
    <div class="section">
      <performance-graph></performance-graph>
    </div>
  </div>
</template>

<script>
export default {
  data(){ return {
    fundData: {}
  }},

  ready(){
    Navi.getNav(this.$route.params.fundname).then(data => this.fundData = data);
    this.$broadcast('updateChart', [this.$route.params.fundname]);
  }
}
</script>

<style scoped lang="scss">
@import "../../sass/variables.scss";


</style>
