<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Supports\Constant;

class CreateBusinessesTable extends Migration
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
        Schema::create('organization_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id');
            $table->date('founded_at')->nullable();
            $table->unsignedInteger('total_employee')->nullable();
            $table->string('annual_revenue')->nullable();
            $table->string('stock_symbol')->nullable();
            $table->string('legal_name')->nullable()->comment('owner or authority');
            $table->string('gln_number')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('tax_certificate')->nullable();
            $table->date('tax_expire')->nullable();
            $table->string('state_tax_id')->nullable();
            $table->string('regional_tax_id')->nullable();
            $table->string('vat_id')->nullable();
            $table->string('vat_document')->nullable();
            $table->string('business_type')->nullable();
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
        Schema::dropIfExists('organization_businesses');

        //Temporary Disable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();
    }
}
