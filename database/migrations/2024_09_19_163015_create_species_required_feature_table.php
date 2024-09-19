<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpeciesRequiredFeatureTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('species_required_feature', function (Blueprint $table) {
            $table->integer('species_id')->unsigned();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('species_required_feature');
    }
}
