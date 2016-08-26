<?php

class ToString {

    public $data;
    public $template;

    function __construct($link, $template) {
        
        global $pdo;

    // получим объект и id
    list($object, $id) = explode(':',$link);

           $query = new MyQuery($object);
           $sql = $query -> getListById($id);
        if($sql!=='') {

       $stmt = $pdo -> query( $sql ); 
    if($stmt) { $data = $stmt -> fetch(PDO::FETCH_ASSOC); }

    $this -> data = $data;
    $this -> template = $template;

        }
    }

    private function Riched() { return ''.Stringf::k2v($this -> template, $this -> data); }
    public function __toString() { return $this -> Riched(); }

}

