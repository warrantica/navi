<template>
  <div class="fundCard" :style="{background:color}">
    <div class="fundCard-name">
      {{ name }}
    </div>
    <div class="fundCard-change">
      {{ navChange }}<span class="fundCard-percent">%</span>
    </div>
    <div class="fundCard-nav">
      {{ pipChange }} from {{ oldNav }} to {{ nav }}
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
    oldNav: 0
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
    });
  }
}
</script>

<style lang="scss" scoped>
@import "../../sass/variables.scss";

.fundCard{
  @include style-card;
  color: white;
  margin: 10px;
}

.fundCard:first-child{
  margin-left: 0;
}

.fundCard-name{
  text-transform: uppercase;
  font-weight: $font-weight-bold;
}

.fundCard-change{
  font-size: 3rem;
  font-weight: $font-weight-light;
}

.fundCard-percent{
  font-size: 1.5rem;
}

.fundCard-nav{
  font-size: 0.7rem;
}
</style>
