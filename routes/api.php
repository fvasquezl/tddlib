<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;


JsonApi::register('v1')->routes(function ($api) {
    $api->resource('posts')->relationships(function ($api){;
        $api->hasOne('categories')->except('replace');
    });

    $api->resource('categories')->relationships(function ($api){
        $api->hasMany('articles')->except('replace','add','remove');
    });
});

