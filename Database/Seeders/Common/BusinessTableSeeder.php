<?php

namespace Modules\Organization\Database\Seeders\Common;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class BusinessTableSeeder
 * @package Modules\Organization\Database\Seeders\Common
 */
class BusinessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
