<?php
namespace esri\geometry;

class geometry {
    protected static $reader;
    public $bbox;
    public $type;
    public $geometry;
    public $meta;
    
    public static function setReader($reader) {
        static::$reader = $reader;
    }
    
    public static function read() {
        $model = new static;
        $model->bbox = static::getBbox();
        return $model;
    }
    
    protected function getPoint() {
        $data = [];
        $data['lon'] = static::$reader->read("d");
        $data['lat'] = static::$reader->read("d");
        return $data;
    }

    protected function getBbox() {
        $reader = static::$reader;
        
        $res =  [
            $reader->read("d"),
            $reader->read("d"),
            $reader->read("d"),
            $reader->read("d"),
        ];
        return implode(',', $res);
    }
    
    /**
     * Process function for loadPolyLineRecord and loadPolygonRecord.
     * Returns geometries array.
     */
    protected function processLineStrings() {
        $reader = static::$reader;
        $numParts = $reader->read("V");
        $numPoints = $reader->read("V");

        $parts = [];
        for ($i = 0; $i < $numParts; $i++) {
            $parts[] = $reader->read("V");
        }

        for ($i = 0; $i < $numPoints; $i++) {
            $this->geometry[] = static::getPoint();
        }
    }     
}