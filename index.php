<?php

    // error_reporting(E_ALL); ini_set("display_errors", "on");
    session_start();

    include('./_Ini/ini.php');
    include('./_myObjects_init.php');
    
    // номер экземпляра приложения
    define('prefix', 1100001001); 
    // создать необходимые ресрсы бд
    MysqlSetup(prefix);

    if (isset($_GET['ajax']))  { include './myObjects_ajax.php'; } 
    // сценарии для обновление и создание
    elseif(isset($_GET['action'])) { include './myObjects_action.php'; }
    // myObjects
    elseif(isset($_GET['myObjects'])) { include('myObjects_route.php'); }
    // default
    else {

        print Filef::Read('./_Gui/page.html');
    }

    
?>