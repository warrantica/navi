<?php

Route::get('/', function () {
  return view('index');
});

Route::get('/fund/{any}', function(){
  return view('index');
})->where('any', '.*');
