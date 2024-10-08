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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('hashid')->nullable()->index();
            $table->string('route_name');
            $table->string('uri_fullkey');
            $table->string('controller');
            $table->string('method');
            $table->string('description')->nullable();
            $table->string('instance_type')->nullable();
            
            $table->timestamps();       
            
            $table->unique(['route_name', 'uri_fullkey']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
