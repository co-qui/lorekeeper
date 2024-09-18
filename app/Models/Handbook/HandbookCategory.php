<?php

namespace App\Models\Handbook;

use Config;
use App\Models\Model;

class HandbookCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sort', 'image_name', 'description', 'parsed_description'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'handbook_categories';
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'name' => 'required|unique:handbook_categories|between:3,100',
        'description' => 'nullable',
        'image' => 'mimes:png,jpg',
    ];
    
    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required|between:3,100',
        'description' => 'nullable',
        'image' => 'mimes:png,jpg',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category of this feature.
     */
    public function handbooks()
    {
        return $this->hasMany('App\Models\Handbook\HandbookPage', 'category_id')->orderBy('sort', 'DESC');
    }

    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/
    
    /**
     * Displays the model's name, linked to its encyclopedia page.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return '<a href="'.$this->url.'" class="display-category">'.$this->name.'</a>';
    }

    /**
     * Gets the file directory containing the model's image.
     *
     * @return string
     */
    public function getImageDirectoryAttribute()
    {
        return 'images/data/handbook-categories';
    }

    /**
     * Gets the file name of the model's image.
     *
     * @return string
     */
    public function getCategoryImageFileNameAttribute()
    {
        return $this->image_name;
    }

    /**
     * Gets the path to the file directory containing the model's image.
     *
     * @return string
     */
    public function getCategoryImagePathAttribute()
    {
        return public_path($this->imageDirectory);
    }
    
    /**
     * Gets the URL of the model's image.
     *
     * @return string
     */
    public function getCategoryImageUrlAttribute()
    {
        if (!$this->image_name) return null;
        return asset($this->imageDirectory . '/' . $this->categoryImageFileName);
    }
}
