<?php

namespace App\Models\Species;

use App\Models\Model;

class SpeciesFeature extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'species_id', 'name',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'species_required_feature';

    /**********************************************************************************************

        RELATIONS


    **********************************************************************************************/

    /**
     * Get the image associated with this record.
     */
    public function image() {
        return $this->belongsTo('App\Models\Feature\FeatureCategory', 'image');
    }

    /**
     * Get the feature category associated with this record.
     */
    public function feature() {
        return $this->belongsTo('App\Models\Feature\FeatureCategory', 'name');
    }
}
