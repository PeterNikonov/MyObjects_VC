<?php

        switch($_GET['ajax']) {
        
        // сменить поле 
        case 'ue_reload':
            $mr = new MyRelation($_GET['name'], null, (int) $_GET['mode']);
            print $mr -> View();
                break;

        // форма создания
        case 'myCreate': 
            if(isset($_GET['table'])) {
                if(isset($reg_object[$_GET['table']])) {
                    
                    $myIndex = new MyGui( $_GET['table'], $reg_property[$_GET['table']]);
                    
                    if(trim($_GET['dsn'])!=='') {
                        
                        $dataObj = new stdClass;

                                      $stringDSN = $_GET['dsn'];
                    $e = explode(',', $stringDSN);
            foreach($e as $c) {
                $ce = explode(':', $c);
                $dataObj -> $ce[0] = $ce[1];
                        }
                             print $myIndex -> CreateForm($dataObj);
                    } else { print $myIndex -> CreateForm(); }
                }
            }
        break;
        // форма редактирования
        case 'myUpdate':
            if(isset($_GET['table'])) {
                if(isset($reg_object[$_GET['table']])) {
                    $myIndex = new MyGui( $_GET['table'], $reg_property[$_GET['table']]);
                    print $myIndex -> UpdateForm( (int) $_GET['id'] );
                }
            }
        break;
        // форма редактирования
        case 'myDelete':
            if(isset($_GET['table'])) {
                if(isset($reg_object[$_GET['table']])) {
                    $myIndex = new MyGui( $_GET['table'], $reg_property[$_GET['table']]);
                    print $myIndex -> DeleteForm( (int) $_GET['id'] );
                }
            }
        break;

    }

