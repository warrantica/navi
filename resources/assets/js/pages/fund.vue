<template>
  <div class="routerWrapper">
    <div class="section">
      This is {{ fundData.name }} <br>
      Nav: {{ fundData.nav }}
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
  }
}
</script>

<style scoped lang="scss">
@import "../../sass/variables.scss";


</style>
