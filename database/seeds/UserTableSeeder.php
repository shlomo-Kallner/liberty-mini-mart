<?php

use Illuminate\Database\Seeder;
use App\Utilities\Permits\Basic;
use Illuminate\Support\Facades\Storage;
use Faker\Generator as Faker;
use App\Utilities\Functions\Functions;
use App\Image;
use App\User; 
use App\UserImage;


class UserTableSeeder extends Seeder
{
    static protected function genFake($faker, bool $asUser = false)
    {
        $tmp = User::createNew(
            $faker->name . '.@#$%^&*',
            $faker->email,
            $asUser ? 'aB123456789' : '12345678912',
            [
                'name' => $faker->imageUrl(),
                'path' => '',
                'alt' => $faker->text(20),
                'caption' => $faker->text(80),
            ]
        );
        if (Functions::testVar($tmp)) {
            if ($asUser) {
                $perm = new Basic($tmp->id);
                $perm->setGuestUser();
                $perm->setAuthUser(true);
                $perm->makeFakes(random_int(1, 4));
            }
            return $tmp;
        } else {
            dd($tmp);
        }
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$faker = new Faker();
        $faker = \Faker\Factory::create();
        $a = false ? random_int(3, 9) : 0;
        $u = User::getNumForVer() + $a;
        for ($i = 0; $i < $u; $i++) {
            User::createNew(
                $faker->name . '.@#$%^&*',
                $faker->email,
                '123456789',
                [
                    'name' => $faker->imageUrl(),
                    'path' => '',
                    'alt' => $faker->text(20),
                    'caption' => $faker->text(80),
                ]
            );
        }

        // create several content creators..
        $creator = User::createNew(
            'painter', 'finger.painter@example.com',
            'fingerPainter123',  1, 1
        );
        //dd($creator);
        $perm = new Basic($creator->id);
        $perm->setGuestUser();
        $perm->setAuthUser();
        $perm->setContentCreator(true);
        $perm->makeFakes(random_int(1, 4));
        
        // create my..
        $admin = User::createNew(
            'artisan', 'artisan@liberty-mini-mart.bit.il',
            'phpMyAdmin127', 1, 1
        );
        
        $perm = new Basic($admin->id);
        $perm->setGuestUser();
        $perm->setAuthUser();
        $perm->setContentCreator();
        $perm->setAdmin(true);
        $perm->makeFakes(random_int(1, 4));

        // create several regular users..
        $user = User::createNew(
            'critic', 'indulgent.critic@example.com',
            'criticalReveiwer123',  1, 1
        );
        $perm = new Basic($user->id);
        $perm->setGuestUser();
        $perm->setAuthUser(true);
        $perm->makeFakes(random_int(1, 4));
        
    }
}
