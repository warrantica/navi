<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Navi</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}" >
</head>
<body>
  <div class="header">
    <div class="header-logo" v-link="'/'">
      <img class="logo" src="/img/logo.png" alt="Navi" />Navi
    </div>
    <input class="header-search" type="text" v-model="currentFundName" @keyup.enter="goToFund">
    <div class="header-toolbar">
      <i ic class="toolbarIcon" v-link="'/fund'">list</i>
    </div>
  </div>
  <div class="content">
    <router-view></router-view>
  </div>

  <!-- Javascript -->
  <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
