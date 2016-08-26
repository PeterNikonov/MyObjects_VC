<?php

// требует установленного и активного расширения php intl
mb_internal_encoding("UTF-8");

$host = 'localhost'; $db = 'RU_CLINICHOST'; $charset = 'utf8'; 
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $user = 'root';
    $pass =  '';

    $opt = array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);

    $pdo = new PDO($dsn, $user, $pass, $opt);

    include('autoloader.php');

    function MysqlSetup($appId) {

    global $pdo;

    $command = str_replace('_prefix_', '%prefix%', Filef::Read('./_Ini/MysqlSetupQueries.sql'));
    $command = Stringf::k2v($command, array('prefix' => $appId));

            $sql_array = explode(';', $command);
    foreach($sql_array as $query) { if(trim($query)!=='') { $pdo -> query($query); } }

    }

?>