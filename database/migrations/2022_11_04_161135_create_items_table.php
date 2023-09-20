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
            $table->string('id')->primary();
            // $table->string('airtable_id')->unique();
            $table->integer('offset_top')->default(0);
            $table->string('object')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->string('description')->nullable();
            $table->string('material')->nullable();
            $table->string('technique')->nullable();
            $table->string('iconography')->nullable();
            $table->string('style')->nullable();
            $table->string('image_src')->nullable();
            $table->string('web_url')->nullable();
            $table->string('collection')->nullable();
            $table->string('year_from')->nullable();
            $table->string('year_to')->nullable();
            // $table->json('description')->nullable();
            // $table->json('author_name')->nullable();
            // $table->json('author_description')->nullable();
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
        Schema::dropIfExists('items');
    }
}
