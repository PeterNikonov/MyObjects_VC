<?php
function Location( $location ) { header('location: '.$location.' '); }

// создание записи
if($_GET['action']=='insert') {
    
            $array = $_POST; 
    foreach($array as $key => $value) {
        // посмотрим ссылки на строки из справочника
        if(isset($reg_object[$key])) {
            if(is_numeric($value) AND $key!=='rate') {
            } else {

            $myQuery = new MyQuery($key, array('name' => $value));
            $pdo -> query($myQuery ->Insert());

            $array[$key] = $pdo -> lastInsertId();

            }
        }
    }

                  $myQuery = new MyQuery($_GET['table'], $array);
    $pdo -> query($myQuery -> Insert());
    
        // - - -
        $lineId = $pdo -> lastInsertId();

    Location('?myObjects='.$_GET['table'].'&message=1');
}

// ап записи
if($_GET['action']=='update') {
    
    $array = $_POST;
    
    foreach($array as $key => $value) {
        // посмотрим ссылки на строки из справочника
        if(isset($reg_object[$key])) {
            if(is_numeric($value) AND $key!=='rate') {
            } else {
   
              if(trim($value)!=='') {
                              $myQuery = new MyQuery($key, array('name' => $value));
                $pdo -> query($myQuery ->Insert());
                $array[$key] = $pdo -> lastInsertId();
                }
            }
        }
    }

                  $myQuery = new MyQuery( $_GET['table'], $array);

    $pdo -> query($myQuery -> Update((int)$_GET['id']));
    Location('?myObjects='.$_GET['table'].'&message=2');
}

// системное удаление
if($_GET['action']=='delete') {

    $trash = new MyGarbage($_GET['table']);
    $trash -> Put((int)$_GET['id']);

    Location('?myObjects='.$_GET['table'].'&message=3');
}
