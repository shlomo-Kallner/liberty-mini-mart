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
        $faker = new Faker();
        for ($i = 0; $i < 50; $i++) {
            User::createNew(
                $faker->name() . '.@#$%^&*',
                $faker->email,
                '123456',
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
            'phpMyAdmin127', [
                'name' => 'liberty-bell-30065_640.png',
                'path' => 'images\site',
                'alt' => 'User Avatar',
                'caption' => 'A Outline of the Liberty Bell.'
            ]
        );
        $perm = new Basic($admin->id);
        $perm->addPermit(Basic::GUEST_USER_ROLE, Basic::READ_LEVEL);
        $perm->addPermit(Basic::AUTH_USER_ROLE, Basic::READ_LEVEL);
        $perm->addPermit(Basic::CONTENT_ROLE, Basic::READ_LEVEL);
        $perm->addPermit(Basic::ADMIN_ROLE, Basic::READ_LEVEL);

        // create several content creators..
        $creator = User::createNew(
            'painter', 'finger.painter@example.com',
            'fingerPainter123', [
                'name' => 'liberty-bell-30065_640.png',
                'path' => 'images\site',
                'alt' => 'User Avatar',
                'caption' => 'A Outline of the Liberty Bell.'
            ]
        );
        $perm = new Basic($creator->id);
        $perm->addPermit(Basic::GUEST_USER_ROLE, Basic::READ_LEVEL);
        $perm->addPermit(Basic::AUTH_USER_ROLE, Basic::READ_LEVEL);
        $perm->addPermit(Basic::CONTENT_ROLE, Basic::READ_LEVEL);
        
        // create several regular users..
        $user = User::createNew(
            'critic', 'indulgent.critic@example.com',
            'criticalReveiwer123', [
                'name' => 'liberty-bell-30065_640.png',
                'path' => 'images\site',
                'alt' => 'User Avatar',
                'caption' => 'A Outline of the Liberty Bell.'
            ]
        );
        $perm = new Basic($user->id);
        $perm->addPermit(Basic::GUEST_USER_ROLE, Basic::READ_LEVEL);
        $perm->addPermit(Basic::AUTH_USER_ROLE, Basic::READ_LEVEL);
        
    }
}