<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::get('/test-redis', function () {
	Redis::set('test', 'Redis работает!');
	return Redis::get('test');
});