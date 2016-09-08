<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Navi</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:100,600">
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}" >
</head>
<body>
  <div class="sidebar">
    <div class="sidebar-logo">Navi</div>
  </div>
  <div class="content">
    <fund-card name="kt-st" color="#2196F3"></fund-card>
    <fund-card name="ktplus" color="#8BC34A"></fund-card>
    <fund-card name="k-fixed" color="#FF5722"></fund-card>
    
    <div width="400px" height="500px">
      <canvas id="chart" width="300" height="300"></canvas>
    </div>
  </div>

  <!-- Javascript -->
  <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
