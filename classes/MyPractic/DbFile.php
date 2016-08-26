<?php

// хранение файлов в бд
class DbFile {
    
    // создать ресурс бд
    public static function SetDb() {
        
        $q = "CREATE TABLE IF NOT EXISTS ".prefix."_upload (
id INT NOT NULL AUTO_INCREMENT,
client INT NOT NULL,
date date,
detail VARCHAR(250) NOT NULL,
name VARCHAR(250) NOT NULL,
type VARCHAR(30) NOT NULL,
size INT NOT NULL,
content MEDIUMBLOB NOT NULL,
PRIMARY KEY(id),
KEY(client)
);";

    query($q);

    }

    // форма для загрузки
    public static function Form($clientId) {

        self::SetDb();

        return '<form action="?action=upload:file&clientId='.$clientId.'" method="post" enctype="multipart/form-data">'
             . '<input name="clientId" type="hidden" value="'.$clientId.'">'
             . '<input name="userfile" type="file"><br>'
             . '<textarea name="detail" placeholder="Описание, комментарий к файлу" class="form-control"></textarea>'
             . '<br><button type="submit" class="btn btn-primary">Загрузить</button>'
             . '</form>';
    }

    // загрузка
    public static function Upload() {

    $u['name'] = $_FILES['userfile']['name'];    
    $u['size'] = $_FILES['userfile']['size'];
    $u['type'] = $_FILES['userfile']['type'];

    $u['client'] = (int) $_POST['clientId'];
    $u['date'] = date('Y-m-d');
    $u['detail'] = $_POST['detail'];

    $tmpName  = $_FILES['userfile']['tmp_name'];
    $fp = fopen($tmpName, 'r');

    $u['content'] = fread($fp, filesize($tmpName));
    fclose($fp);
    
              $cq = new Query(prefix."_upload", $u);
        query($cq -> Insert());

    }

    // отдача
    public static function Download($id) {

        $query = "SELECT name, type, size, content " .
        "FROM ".prefix."_upload WHERE id = '$id'";

   $r = query($query);
if($r) {
    $file = $r -> fetch(PDO::FETCH_OBJ);

header("Content-length: ".$file -> size." ");
header("Content-type: ".$file -> type."");
header("Content-Disposition: attachment; filename=".$file -> name."");
echo $file -> content;

        }
    }

    // удалить файл
    public static function Delete($id) { query("delete from ".prefix."_upload WHERE id = ". (int) $id.""); }

}
