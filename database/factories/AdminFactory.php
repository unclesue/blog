<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 11:21
 */

$factory->define(App\Admin::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->firstName,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
