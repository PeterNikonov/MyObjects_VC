<?php


/*
 * Мусорка - Получить удаленные записи для исключения из выборки некоего объекта
 */

class MyGarbage {

    private $obj;
    
    function __construct($obj) { $this -> obj = $obj; }

    // "куча" удаленных объектов
    final public function Bunch() {

        global $pdo;

        $stmt = $pdo -> query("SELECT object_id FROM ".prefix."_trash WHERE object_type = ".$pdo -> quote($this -> obj)."");
        $rows = $stmt -> fetchAll(PDO::FETCH_COLUMN);

        return $rows;

    }

    // "положить" id в мусорку
    final public function Put($id) {
        
        global $pdo;

        $data = array('object_id' => (int) $id, 'object_type' => $this->obj);

                       $myQuery = new MyQuery('trash', $data);
        $pdo -> query( $myQuery ->Insert());
    }
}
