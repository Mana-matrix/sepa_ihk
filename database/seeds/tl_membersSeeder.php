<?php

use Illuminate\Database\Seeder;

class tl_membersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Create 50 product records
        for ($i = 0; $i < 150; $i++) {
            $fname=$faker->firstName;
            $lname=$faker->lastName;
            App\tl_member::create([
                'id'=>$i,
                'firstname' =>$fname,
                'lastname' =>$lname,
                'xt_bank_owner' =>$fname.' '.$lname,
                'xt_bic' =>$faker->swiftBicNumber,
                'xt_iban' =>$faker->iban('DE','5500',11),
                'xt_memberfee' =>$faker->randomFloat(2,0,100),
                'disable' => $faker->numberBetween(0,1),
                'gender' => random_int(0,1) ==0 ? 'female':'male',
            ]);
        }
    }

    private function gender(){
       return random_int(0,1) ==0 ? 'female':'male';
    }
}
