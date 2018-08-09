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
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$faker = new Faker();
        $faker = \Faker\Factory::create();
        $u = User::getNumForVer()
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

        // create several content creators..
        $creator = User::createNew(
            'painter', 'finger.painter@example.com',
            'fingerPainter123',  1, 1
        );
        $perm = new Basic($creator->id);
        $perm->setGuestUser();
        $perm->setAuthUser();
        $perm->setContentCreator(true);
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
