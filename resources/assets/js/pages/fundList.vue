<template>
  <div class="routerWrapper">
    <div class="section fundList">
      <div class="cardHeader">Saved Funds</div>
      <div class="fundList-item" v-for="fund in funds">
        <div class="fundList-color" :style="{background:fund.color}"></div>
        <div class="fundList-name">{{ fund.name | uppercase }}</div>
        <div class="fundList-delete" @click="deleteFund(fund.name)">
          <i ic>delete</i>
        </div>
      </div>
      <form class="fundList-add" method="post" action="/api/fund">
        <div class="fundList-add-row">
          <div class="fundList-color" :style="{background: addColor}"></div>
          <input class="fundList-add-name" name="name" type="text">
          <input type="hidden" name="color" v-model="addColor">
          <button class="fundList-add-button" type="submit" @click.prevent="addFund">Add</button>
        </div>
        <div class="fundList-add-row">
          <color-select color="#E91E63"></color-select><!--  pink -->
          <color-select color="#F44336"></color-select><!--  red -->
          <color-select color="#FF5722"></color-select><!--  orange -->
          <color-select color="#673AB7"></color-select><!--  purple -->
          <color-select color="#2196F3"></color-select><!--  blue -->
          <color-select color="#009688"></color-select><!--  teal -->
          <color-select color="#8BC34A"></color-select><!--  green -->
          <color-select color="#FFC107"></color-select><!--  yellow -->
          <color-select color="#795548"></color-select><!--  brown -->
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data(){ return {
    funds: [],
    addColor: '#2196F3'
  }},

  methods: {
    addFund(){
      let form = document.querySelector('.fundList-add');
      Navi.addFund({
        'name': form.name.value,
        'color': form.color.value
      }).then(data => {
        console.log(data);
        this.$emit('updateData');
      }).catch(reason => {
        console.log('addFund() error: ' + JSON.stringify(reason));
      });
    },

    deleteFund(name){
      console.log("delete " + name);
    }
  },

  ready(){
    this.$emit('updateData');
  },

  events: {
    'updateData'(){
      Navi.getAllFunds().then(data => this.funds = data);
    },

    'selectColor'(color){
      this.addColor = color;
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
  padding: 10px 0;
  background: rgba(255, 255, 255, 0);
  @include transition-normal;
}

.fundList-item:hover{
  background: rgba(255, 255, 255, 0.12);
}

.fundList-color{
  display: inline-block;
  margin-right: 10px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
}

.fundList-name{
  flex: 1;
}

.fundList-delete{
  opacity: 0;
  @include transition-normal;
  cursor: pointer;
  padding: 0 10px;
  color: rgba(255, 255, 255, 0.5);
}

.fundList-delete:hover{ color: rgba(255, 255, 255, 1); }

.fundList-item:hover .fundList-delete{
  opacity: 1;
}

.fundList-add-row{
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  margin: 10px 0;
}

.fundList-add-name{
  @include textbox;
  background: rgba(255, 255, 255, 0.24);
  display: inline-block;
}
</style>
