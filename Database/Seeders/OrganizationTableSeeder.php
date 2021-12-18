<?php

namespace Modules\Organization\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

/**
 * @class OrganizationTableSeeder
 * @package Modules\Organization\Database\Seeders
 */
class OrganizationTableSeeder extends Seeder
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
