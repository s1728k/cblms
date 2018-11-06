<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license', function (Blueprint $table) {
            $table->increments('id');
            $table->string('license_key')->unique();
            $table->integer('total_licenses');
            $table->integer('activated_licenses');
            $table->unsignedInteger('created_by');
            $table->date('expiry_date');
            $table->unsignedInteger('price_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('license');
    }
}
