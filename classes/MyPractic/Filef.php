<?php


class Filef {

    public static function Read($fileName) {

        $handle = fopen($fileName, 'rb');

               $variable = fread($handle, filesize($fileName)); fclose($handle);
        return $variable;
    }
}
