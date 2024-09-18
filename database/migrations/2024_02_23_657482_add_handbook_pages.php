<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHandbookPages extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        //

        Schema::create('handbook_pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('category', 50)->nullable();
            $table->string('title', 150);
            $table->text('text')->nullable();
            $table->text('parsed_text')->nullable()->default(null);
            $table->boolean('is_visible')->default(1); // visibility to users without editing powers
            $table->timestamps(); // for showing a "last edited" date
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        //
        Schema::dropIfExists('handbook_pages');
    }
}
