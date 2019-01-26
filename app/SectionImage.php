<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot, 
    App\Utilities\Functions\Functions,
    App\Utilities\ImagePivotAPI,
    App\Utilities\ImagePivot,
    App\Section;

class SectionImage extends Pivot implements ImagePivotAPI
{
    use ImagePivot;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'section_images';

    static public function getOthersKey() 
    {
        return 'section_id';
    }

    static public function getIdFromOther($other)
    {
        return Section::getIdFrom($other, false, null);
    }

    static public function getOtherClassName()
    {
        return 'App\Section';
    }

    static public function createNewFrom(Section $section, bool $retObj = false) 
    {
        return self::createNew($section, $section->image_id, $retObj);
    }

    public function section()
    {
        return $this->belongsTo('App\Section', 'section_id');
    }
}
