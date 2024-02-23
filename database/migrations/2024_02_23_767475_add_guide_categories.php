<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuideCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 
        Schema::table('guide_pages', function(Blueprint $table) {
            $table->integer('sort')->unsigned()->default(0);
            $table->integer('category_id')->unsigned();
            $table->dropColumn('category');
        });

        Schema::create('guide_categories', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('parsed_description')->nullable()->default(null);
            $table->string('image_name', 512)->nullable();
            $table->integer('sort')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('guide_categories');
        Schema::table('guide_pages', function(Blueprint $table) {
            $table->dropColumn('sort');
            $table->string('category', 50)->nullable();
            $table->dropColumn('category_id');
        });
    }
}
