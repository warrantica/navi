<template>
  <div class="routerWrapper">
    <div class="section fundList">
      <div class="cardHeader">Saved Funds</div>
      <div class="fundList-item" v-for="fund in funds">
        <div class="fundList-color" :style="{background:fund.color}"></div>
        {{ fund.name | uppercase }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){ return {
    funds: []
  }},

  methods: {
  },

  ready(){
    this.$emit('updateData');
  },

  events: {
    'updateData'(){
      Navi.getAllFunds().then(data => this.funds = data);
    }
  },

  route: {
    data(transition){
      this.$dispatch('updateCurrentFundName', '');
      transition.next();
    }
  }
}
</script>

<style scoped lang="scss">
@import "../../sass/variables.scss";

.fundList{
  box-sizing: border-box;
  @include style-card;
  display: block;
  margin: 20px auto;
  max-width: 500px;
}

.fundList-item{
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  margin: 10px 0;
}

.fundList-color{
  display: inline-block;
  margin-right: 10px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
}
</style>
