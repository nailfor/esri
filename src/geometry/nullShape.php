<?php
namespace esri\geometry;

class nullShape extends geometry {
    public static function read() {
        return [];
    }
}
