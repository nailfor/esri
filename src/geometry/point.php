<?php
namespace esri\geometry;

class point extends geometry{
    public $type = 'point';
    
    public static function read() {
        $model = parent::read();
        $model->geometry = [static::getPoint()];

        return $model;
    }
}
