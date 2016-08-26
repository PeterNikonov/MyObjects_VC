<?php



// класс генерирует SQL запросы
class MyQuery  {

    private $table; // таблица к которой применяем запрос
    private $garbage = array(0); // массив скрытых объектов

    private $legal = false; // существующие поля таблицы
    private $prepared = false; // подготовленные 
    private $data = false; // 

    private $orderBy = 'ORDER BY name'; // 

    public function __construct($table, $data = false) {

        $this -> table = $table;

        // если прилетел массив данных - речь о вставке апдейте
        if($data) {
            
            $this -> data = $data;

            global $pdo;

            $this -> legal = $this -> setLegal( $pdo );
            $this -> prepared = $this -> setPrepared( $pdo );

            unset($data);

        // массива нет - работаем с выборкой
        } else {

            // получим удаленные записи
            $garbage = new MyGarbage( $table );
            $bunch = $garbage -> Bunch();
 
            if(is_array($bunch) && count($bunch) > 0) { $this -> garbage = $bunch; }

        }
    }
    
    // условие сортировки
    public function SetOrderBy($fieldName = '') {
        if(trim($fieldName)!=='') { $this -> orderBy = 'ORDER BY '.$fieldName; }
    }

    private function setLegal( PDO $pdo )  {

                                 // данные о структуре таблиц - часто используются, кешируем
                                 $cache_filename = "SHOW_COLUMNS_FROM_".prefix."_".$this -> table."";
          $cached = Cachef::read($cache_filename);
       if($cached)
       { $fields = unserialize($cached);
       } else {

                  $stmt = $pdo -> query("SHOW COLUMNS FROM `".prefix."_".$this -> table."`");
        $fields = $stmt -> fetchAll(PDO::FETCH_COLUMN);

        Cachef::write(serialize($fields), $cache_filename);

       } return $fields;
    }

    private function setPrepared( PDO $pdo ) {

        if($this -> data) {
            
            $arr = array();

            foreach($this -> data as $fieldname => $value) 
            {
                // если поле есть таблице
                if(in_array($fieldname, $this -> legal)) {
                $arr[$fieldname] = $pdo -> quote($value);
                }
            } return $arr;
        }
    }

    // - U
    public function Update( $id ) {
       if($id && $this -> prepared) {
       foreach($this -> prepared as $f => $v) {
            $arr[] = " `$f` = $v ";
            }
           return "UPDATE ".prefix."_".$this -> table." SET ".implode(',',$arr)." WHERE id = ". (INT) $id.";"; // что бы туда не прилетело    
        } else { return ''; }
    }
    // - C
        public function Insert() {
        if($this -> prepared) {
            foreach($this -> prepared as $f => $v) { $ins_f[] = "`$f`"; $ins_v[] = $v; }
            return  "INSERT INTO ".prefix."_".$this-> table." (".implode(',',$ins_f).") VALUES (".implode(',',$ins_v).");";
        } else { return ''; }
    }

    // получить записи удовлетворяющие условиям
    public function getListByClause($stringDSN) {

        global $pdo;

                $e = explode(',', $stringDSN);
        foreach($e as $c) {

            $ce = explode(':', $c);
            $ca[] = "".$ce[0]." = ".$pdo -> quote($ce[1]).""; 
        }

        return "SELECT * FROM ".prefix."_".$this -> table." WHERE id NOT IN (".implode(',',  $this-> garbage ).") AND ".implode('AND ', $ca)."".$this -> orderBy;

    }

    // получить записи по списку идентификторов
    public function getListById($idArray) {

        if(!is_array($idArray) && is_numeric($idArray)) { $idArray = array($idArray); }

                // исключим  пересечения с удаленными записями
                $legalId = @array_diff( $idArray, $this -> garbage );
             if($legalId) {
        foreach($legalId as $id) { $idin[] = (int) $id; }

                 return "SELECT * FROM ".prefix."_".$this -> table." WHERE id IN (".implode(',', $idin ).")".$this -> orderBy;

        } else { return ""; }
    }

    public function getFirstLetters($field = 'name') {
        return "SELECT LEFT( ".$field.", 1 ) AS letter FROM ".prefix."_".$this -> table." WHERE id NOT IN (".implode(',',  $this -> garbage ).") GROUP BY letter";
    }

    public function getList($fields = '*') {
        return "SELECT $fields FROM ".prefix."_".$this -> table." WHERE id NOT IN (".implode(',',  $this -> garbage ).")".$this -> orderBy;
    }

    public function getListByLike($like) {
        return "SELECT * FROM ".prefix."_".$this -> table." WHERE $like AND id NOT IN (".implode(',',  $this -> garbage ).")".$this -> orderBy;
    }
}