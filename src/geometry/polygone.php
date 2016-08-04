<?php
namespace esri\geometry;

class polygone extends geometry{
    public $type = 'polygon';
    
    public static function read() {
        $model = parent::read();
        $model->processLineStrings();

        return $model;
    }
}

