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
        Schema::create('caren_extras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('standard');
            $table->unsignedBigInteger('caren_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('extra_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('vendor_id')->nullable()->unsigned()->index();

            $table->text('caren_data')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('extra_id')->references('id')->on('extras')->onDelete('set null');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caren_extras');
    }
};
