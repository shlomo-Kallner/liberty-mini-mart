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
                $tu = User::getUserId($tmp);
                $perm = new Basic($tu);
                $perm->setGuestUser();
                $perm->setAuthUser();
                $perm->makeFakes(false ? random_int(1, 4) : 1, false, 1);
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
        $ci = User::getUserId($creator);
        $perm = new Basic($ci);
        $perm->setGuestUser();
        $perm->setAuthUser();
        $perm->setContentCreator();
        $perm->makeFakes(false ? random_int(1, 4) : 1, false, 1);
        
        // create my..
        $admin = User::createNew(
            'artisan', 'artisan@liberty-mini-mart.bit.il',
            'phpMyAdmin127', 1, 1
        );
        $ai = User::getUserId($admin);
        $perm = new Basic($ai);
        $perm->setGuestUser();
        $perm->setAuthUser();
        $perm->setContentCreator();
        $perm->setAdmin();
        $perm->makeFakes(false ? random_int(1, 4) : 1, false, 1);

        // create several regular users..
        $user = User::createNew(
            'critic', 'indulgent.critic@example.com',
            'criticalReveiwer123',  1, 1
        );
        $ui = User::getUserId($user);
        $perm = new Basic($user->id);
        $perm->setGuestUser();
        $perm->setAuthUser();
        $perm->makeFakes(false ? random_int(1, 4) : 1, false, 1);

        for ($i = 0; $i < 20; $i++) {
            self::genFake($faker, true);
        }
        
    }
}
