<?php
namespace esri;

class reader{
    private $file;
    private $is_open;
    private $dir;
    protected static $lengths = [
        'd' => 8,
        'V' => 4,
        'N' => 4,
    ];
    
    public function getDir() {
        return $this->dir;
    }
    
    public function open($path) {
        if (!file_exists($path)) {
            throw new \Exception('file not found');
        }
        else if (is_dir($path)){
            $this->dir = $path;
            $files = glob($path.'/*.shp');
            foreach ($files as $file) {
                $path = $file;
                break;
            }
        }
        else {
            $this->dir = dirname($path);
        }
        
        $this->file = fopen($path, "rb");
        $this->is_open = true;
    }
    
    public function seek($position) {
        if (!$this->is_open) {
            throw new \Exception('file not open!');
        }
        fseek($this->file, 100, SEEK_SET);
    }
    
    protected function dataLength($type) {
        if (isset(static::$lengths[$type])) {
            return static::$lengths[$type];
        }

        return NULL;
    }

    /**
     * Low-level data pull.
     */
    public function read($type) {
        $length = $this->dataLength($type);
        if ($length) {
            $data = fread($this->file, $length);
            if ($data) {
                $tmp = unpack($type, $data);
                return current($tmp);
            }
        }

        return NULL;
    }
    
    public function getPosition() {
        return ftell($this->file);
    }
}