<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddSpeciesRequiredFeatureToSpeciesesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('specieses', function (Blueprint $table) {
            $table->string('species_required_feature')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specieses', function (Blueprint $table) {
            $table->dropColumn('species_required_feature');
        });
    }
}