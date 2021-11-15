<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->string("address");
            $table->string("image")->nullable();
            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();
            $table->json("hours")->nullable();
            $table->string("website")->nullable();
            $table->string("facebook")->nullable();
            $table->string("ig")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->timestamps();

            $table->integer("city_id");
            $table->integer("user_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
