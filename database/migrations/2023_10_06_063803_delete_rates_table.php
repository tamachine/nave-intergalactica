<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rates');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->index();
            $table->string('name');
            $table->decimal('rate', 12, 6);

            $table->timestamps();
        });
    }
};
