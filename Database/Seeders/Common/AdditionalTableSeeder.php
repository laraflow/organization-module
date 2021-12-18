<?php

namespace Modules\Organization\Database\Seeders\Common;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class AdditionalTableSeeder
 * @package Modules\Organization\Database\Seeders\Common
 */
class AdditionalTableSeeder extends Seeder
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
