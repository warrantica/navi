<template>
  <div class="routerWrapper centered">
    <div class="centered-section">
      <h1 class="header">Add Mutual Fund</h1>
      <form class="" action="/api/fund" method="post">
        <input type="text" class="formInput" name="name">
        <input type="text" class="formInput" name="color">
      </form>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped lang="scss">
@import "../../sass/variables.scss";

.centered{
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.centered-section{
  @include style-card;
  display: inline-block;
}

.header{
  font-size: 3rem;
  font-weight: $font-weight-light;
}

</style>
