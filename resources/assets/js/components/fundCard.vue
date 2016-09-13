<template>
  <div class="fundCard" :style="{background:color}" v-link="{ name: 'fund', params: { fundname: this.name }}">
    <div class="fundCard-name">
      {{ name }}
    </div>
    <div class="fundCard-change">
      {{ navChange }}<span class="fundCard-percent">%</span>
    </div>
    <div class="fundCard-nav">
      {{ pipChange }} from {{ navFrom }} to {{ nav }}
    </div>
    <div class="fundCard-date">
      From {{ dateFrom }} to {{ dateTo }}
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
    navFrom: 0,
    nav: 0,
    dateFrom: '',
    dateTo: ''
  }},

  computed: {
    pipChange(){
      if(this.nav === 0) return '-';
      let change = Math.round((this.nav - this.navFrom)*10000);
      return change > 0 ? '+' + change : change;
    },

    navChange(){
      if(this.nav === 0) return '-';
      let change = ((this.nav - this.navFrom)*100/this.navFrom).toFixed(4);
      return change > 0 ? '+' + change : change;
    }
  },

  ready(){
    Navi.getNav(this.name).then(data => {
      this.navFrom = data.navFrom;
      this.nav = data.nav;
      this.dateFrom = moment(data.dateFrom).format('DD/MM/YYYY');
      this.dateTo = moment(data.dateTo).format('DD/MM/YYYY');
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
  cursor: pointer;
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

.fundCard-nav, .fundCard-date{
  font-size: 0.7rem;
}
</style>
