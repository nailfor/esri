<?php
namespace esri\geometry;

class polyline extends geometry{
    public $type = 'linestring';
            
    public static function read() {
        $model = parent::read();
        $model->processLineStrings();

        return $model;
    }
    
}

