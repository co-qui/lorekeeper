<?php

namespace App\Models\Guide;

use App\Models\Model;
use Illuminate\Support\Str;


class GuidePage extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'text', 'parsed_text', 'is_visible', 'sort', 'category_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guide_pages';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'title' => 'required|between:3,150',
        'text' => 'nullable',
    ];
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'title' => 'required|between:3,150',
        'text' => 'nullable',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category of this feature.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Guide\GuideCategory');
    }

    
    /**********************************************************************************************
    
        SCOPES

    **********************************************************************************************/


    /**
     * Scope a query to only include visible posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }


    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/


    /**
     * Get the guide slug.
     *
     * @return bool
     */
    public function getSlugAttribute()
    {
        return $this->id . '.' . Str::slug($this->title);
    }

    /**
     * Displays the news post title, linked to the news post itself.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return '<a href="'.$this->url.'">'.$this->title.'</a>';
    }

    /**
     * Gets the news post URL.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return url('guides/'.$this->slug);
    }
}
