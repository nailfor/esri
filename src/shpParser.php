<?php
namespace esri;

use XBase\Table;

class shpParser {
    public $path;
    protected $reader;
    protected $table;
    
    //support types
    protected static $types = [
        'Null Shape'    => 'esri\geometry\nullShape',
        'Point'         => 'esri\geometry\point',
        'PolyLine'      => 'esri\geometry\polyline',
        'Polygon'       => 'esri\geometry\polygone',
        'MultiPoint'    => 'esri\geometry\multiPoint',
    ];
    
    //known types
    protected static $geomTypes = [
        0 => 'Null Shape',
        1 => 'Point',
        3 => 'PolyLine',
        5 => 'Polygon',
        8 => 'MultiPoint',
        11 => 'PointZ',
        13 => 'PolyLineZ',
        15 => 'PolygonZ',
        18 => 'MultiPointZ',
        21 => 'PointM',
        23 => 'PolyLineM',
        25 => 'PolygonM',
        28 => 'MultiPointM',
        31 => 'MultiPatch',
    ];

    protected function geoTypeFromID($id) {
        if (isset(static::$geomTypes[$id])) {
            return static::$geomTypes[$id];
        }

        return NULL;
    }    
    
    public function __construct($properties) {
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
        
        $reader = new reader();
        $reader->open($this->path);
        $this->reader = $reader;

        $dir = $reader->getDir();
        $files = glob($dir.'/*.dbf');
        foreach ($files as $file) {
            $this->table = new Table($file);
            break;
        }
        
    }

    public function getRecords() {
        $this->reader->seek(100);

        $shpData = [];
        while ($record = $this->getRecord()) {
            if ($record) {
                $shpData[] = $record;
            }
        }
        return $shpData;
    }
    
    protected function getRecord() {
        $reader = $this->reader;
        $recordNumber = $reader->read("N");
        if (!$recordNumber) {
            return null;
        }
        
        $reader->read("N"); // unnecessary data.
        $shape_type = $reader->read("V");
        $name = $this->geoTypeFromID($shape_type);

        $result = '';
        geometry\nullShape::setReader($reader);
        if (isset(static::$types[$name])) {
            $model = static::$types[$name];
            $result = $model::read();

            $meta = [];
            if ($this->table) {
                $record = $this->table->nextRecord();
                $columns = array_keys($record->getColumns());
                foreach ($columns as $column) {
                    $meta[$column] = $record->$column;
                }
            }

            $result->meta = $meta;
        }

        return $result;
    }    

    private function getHeaders() {
        $reader = $this->reader;
        $reader->seek(24);
        $length = $reader->read("N");
        $reader->seek(32);
        $shape_type = $this->geoTypeFromID($reader->read("V"));

        return [
            'length' => $length,
            'shapeType' => [
                'id' => $shape_type,
                'name' => $this->geoTypeFromID($shape_type),
            ],
            'bbox' => $this->getBbox(),
        ];
    }
}
