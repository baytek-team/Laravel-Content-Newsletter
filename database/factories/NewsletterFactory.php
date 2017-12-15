<?php

use Baytek\Laravel\Content\Types\Newsletter\Models\Newsletter;

$factory->define(Newsletter::class, function (Faker\Generator $faker) {

    $title = $faker->sentence();

    return [
        'key' => str_slug($title),
        'title' => $title,
        'content' => null,
        'status' => Newsletter::APPROVED,
        'language' => App::getLocale(),
    ];
});
