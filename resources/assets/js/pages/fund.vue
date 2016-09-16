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
    <div class="section compareList">
      <div class="cardHeader">Compare</div>
      <input type="text" class="compareTextBox" v-for="fund in compareTo"
             track-by="$index"
             v-model="fund">
      <button class="button" @click="addMoreFundToCompare">+</button>
      <button class="button" @click="updateData">Update</button>
    </div>
  </div>
</template>

<script>
export default {
  data(){ return {
    currentFundName: this.$route.params.fundname,
    compareTo: [''],
    fundData: {}
  }},

  methods: {
    updateData(){
      Navi.getNav(this.$route.params.fundname).then(data => this.fundData = data);
      this.$broadcast('updateChart', this.compareTo.concat(this.$route.params.fundname));
    },

    goToFund(){
      this.$router.go({name: 'fund', params: {fundname: this.currentFundName}});
      this.updateData();
    },

    addMoreFundToCompare(){
      this.compareTo.push('');
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

.compareTextBox{
  display: block;
  padding: 5px 10px;
  margin: 5px 0;
  background: $grey;
  @include grey-border;
  font: 1rem $main-font;
  color: $white;
  border-radius: 2px;
  text-transform: uppercase;
}

.button{
  
}
</style>
