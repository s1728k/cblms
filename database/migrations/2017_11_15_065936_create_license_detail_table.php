<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('license_id')->unsigned();
            $table->foreign('license_id')
                  ->references('id')->on('license')
                  ->onDelete('cascade');
            $table->string('hardware_code');
            $table->string('computer_name');
            $table->string('computer_user');
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // $table->dropForeign('posts_user_id_foreign');
        // $table->dropForeign(['user_id']);
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('license_detail');
    }
}
