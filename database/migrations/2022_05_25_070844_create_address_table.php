<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('user_address', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('phone');
        //     $table->integer('user_id');
        //     $table->integer('landmark');
        //     $table->string('city');
        //     $table->string('state');
        //     $table->string('country');
        //     $table->string('postal_code');
        //     $table->string('location_type');
        //     $table->string('address_type');
        //     $table->enum('is_primary',[0,1])->default(0);
        //     $table->enum('status', [0,1])->default(0);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
