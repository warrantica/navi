<template>
  <div class="routerWrapper">
    <div class="graph section">
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
    updateData(){ this.$emit('updateData'); },

    addMoreFundToCompare(){
      this.compareTo.push('');
    }
  },

  ready(){
    this.updateData();
  },

  events: {
    'updateData'(){
      Navi.getNav(this.$route.params.fundname).then(data => this.fundData = data);
      this.$broadcast('updateChart', this.compareTo.concat(this.$route.params.fundname));
    }
  },

  route: {
    data(transition){
      this.$dispatch('updateCurrentFundName', transition.to.params.fundname);
      transition.next();
    }
  }
}
</script>

<style scoped lang="scss">
@import "../../sass/variables.scss";

.graph{
  margin-top: 10px;
}

.compareTextBox{
  @include textbox;
  text-transform: uppercase;
}
</style>
