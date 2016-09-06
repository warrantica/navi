<template>
  <div class="fundCard">
    <div class="fundCard-name">
      {{ name }}
    </div>
    <div class="fundCard-nav">
      {{ nav }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    name: String
  },

  data(){ return {
    nav: 0
  }},

  ready(){
    Navi.getNav(this.name).then(data => this.nav = data.nav);
  }
}
</script>

<style lang="sass" scoped>
@import "../../sass/variables.scss";

.fundCard{
  @include style-card;
}

.fundCard-name{
  font-size: 1.5rem;
}
</style>
