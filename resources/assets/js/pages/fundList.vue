<template>
  <div class="routerWrapper">
    <div class="section fundList">
      <div class="cardHeader">Fund list</div>
      <table>
        <tr>
          <th>Name</th>
        </tr>
        <tr v-for="fund in funds">
          <td>
            {{ fund.name | uppercase }}
          </td>
        </tr>
      </table>
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
  margin: 20px;
}
</style>
