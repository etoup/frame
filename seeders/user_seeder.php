<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Illuminate\Hashing\BcryptHasher;
use Hyperf\Di\Annotation\Inject;
use App\Model\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hash = new BcryptHasher();
        $user = new User();
        $user->username = '18674049588';
        $user->real_name = '李白';
        $user->password = $hash->make('049588');
        $user->super = 80;
        $user->save();
    }
}
