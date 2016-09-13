<template>
  <div class="routerWrapper">
    <div class="section fundCardList">
      <fund-card v-for="fund in fundData" :name="fund.name" :color="fund.color"></fund-card>
      <div class="fundCard-newButton" v-link="{path:'/fund/add'}">Add new mutual fund</div>
    </div>

    <div class="section">
      <performance-graph></performance-graph>
    </div>
  </div>
</template>

<script>
window.Navi = require('../naviAPI.js');
window.Vars = require('../vars.js');

export default{
  data(){ return{
    fundData: []
  }},

  ready(){
    Navi.getAllFunds().then(data => {
      this.fundData = data;

      let fundNames = [];
      for(let fund of data) fundNames.push(fund.name);
      this.$broadcast('updateChart', fundNames);
    });
  }
}
</script>

<style lang="scss">
@import "../../sass/variables.scss";

.fundCardList{
  display: flex;
  flex-flow: row;
  align-items: stretch;
}

.fundCard-newButton{
  @include style-card;
  margin: 10px;
  display: flex;
  flex-flow: row;
  align-items: center;
}
</style>
