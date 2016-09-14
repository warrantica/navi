<template>
  <div class="routerWrapper">
    <div class="section">
      <input class="nameTextBox"
             type="text"
             v-model="currentFundName"
             @keyup.enter="goToFund">
    </div>
    <div class="section">
      <performance-graph></performance-graph>
    </div>
  </div>
</template>

<script>
export default {
  data(){ return {
    currentFundName: this.$route.params.fundname,
    fundData: {}
  }},

  methods: {
    updateData(){
      Navi.getNav(this.$route.params.fundname).then(data => this.fundData = data);
      this.$broadcast('updateChart', [this.$route.params.fundname]);
    },

    goToFund(){
      this.$router.go({name: 'fund', params: {fundname: this.currentFundName}});
      this.updateData();
    }
  },

  ready(){
    this.updateData();
  }
}
</script>

<style scoped lang="scss">
@import "../../sass/variables.scss";

.nameTextBox{
  display: block;
  box-sizing: border-box;
  padding: 10px 20px;
  margin: 10px 0;
  background: $grey;
  @include grey-border;
  width: 100%;
  font: 2rem $main-font;
  font-weight: $font-weight-light;
  color: $white;
  border-radius: 2px;
  text-transform: uppercase;
}
</style>
