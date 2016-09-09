<template>
  <div class="fundCard" :style="{background:color}">
    <div class="fundCard-name">
      {{ name }}
    </div>
    <div class="fundCard-fullName">
      {{ fullName }}
    </div>
    <div class="fundCard-nav">
      {{ nav }}
    </div>
    <div class="fundCard-change">
      {{ pipChange }} pips ({{ navChange }}%)
    </div>
  </div>
</template>

<script>
export default {
  props: {
    name: String,
    color: {
      type: String,
      default: '#795548'
    }
  },

  data(){ return {
    nav: 0,
    oldNav: 0,
    fullName: ''
  }},

  computed: {
    pipChange(){
      if(this.nav === 0) return '-';
      let change = Math.round((this.nav - this.oldNav)*10000);
      return change > 0 ? '+' + change : change;
    },

    navChange(){
      if(this.nav === 0) return '-';
      let change = ((this.nav - this.oldNav)*100/this.oldNav).toFixed(4);
      return change > 0 ? '+' + change : change;
    }
  },

  ready(){
    Navi.getNav(this.name).then(data => {
      this.nav = data.nav;
      this.oldNav = data.oldNav;
      this.fullName = data.fullName;
    });
  }
}
</script>

<style lang="scss" scoped>
@import "../../sass/variables.scss";

.fundCard{
  @include style-card;
  color: white;
  display: inline-block;
}

.fundCard:first-child{
  margin-left: 0;
}

.fundCard-name{
  font-size: 1.5rem;
  text-transform: uppercase;
}

.fundCard-fullName, .fundCard-change{
  font-size: 0.7em;
}
</style>
