<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('fullname', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->date('birthdate')->nullable();
            $table->string('sex')->nullable();
            $table->string('phone')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('social_facebook', 50)->nullable();
            $table->string('social_twitter', 50)->nullable();
            $table->string('social_youtube', 100)->nullable();
            $table->string('social_instagram', 100)->nullable();
            $table->string('social_snapchat', 100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')
              ->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_profiles');
    }
}
