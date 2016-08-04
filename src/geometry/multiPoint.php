<?php
namespace esri\geometry;

class multiPoint extends geometry{
    public $type = 'multipoint';
    
    public static function read() {
        $model = parent::read();
        
        $reader = static::$reader;
        $numGeometries = $reader->read("d");
        for ($i = 0; $i < $numGeometries; $i++) {
            $model->geometry[] = static::getPoint();
        }
        
        return $model;
    }
}
