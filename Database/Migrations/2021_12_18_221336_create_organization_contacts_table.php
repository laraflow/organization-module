<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Supports\Constant;

class CreateOrganizationContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Temporary Disable Foreign Key Constraints
        Schema::disableForeignKeyConstraints();

        //Table Structure
        Schema::create('organization_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->enum('enabled', array_keys(Constant::ENABLED_OPTIONS))
                            ->default(Constant::ENABLED_OPTION)->nullable();
            $table->foreignId('created_by')->index()->nullable();
            $table->foreignId('updated_by')->index()->nullable();
            $table->foreignId('deleted_by')->index()->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Temporary Disable Foreign Key Constraints
        Schema::disableForeignKeyConstraints();

        //Remove Table Structure
        Schema::dropIfExists('organization_contacts');

        //Temporary Disable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();
    }
}
