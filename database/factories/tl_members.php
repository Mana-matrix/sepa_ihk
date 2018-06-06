<?php

use Faker\Generator as Faker;

$factory->define(App\tl_member::class, function (Faker $faker) {
    $fname=$faker->firstName;
    $lname=$faker->lastName;
    return [
        'firstname' =>$fname,
        'lastname' =>$lname,
        'xt_bank_owner' =>$fname.' '.$lname,
        'xt_bic' =>$faker->swiftBicNumber,
        'xt_iban' =>$faker->iban(),
        'disable' => $faker->numberBetween(0,1),
    ];

});
