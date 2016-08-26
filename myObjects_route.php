<?php

    //
    if(isset($reg_object[$_GET['myObjects']])) {
 
        $myGui = new MyGui($_GET['myObjects'], $reg_property[$_GET['myObjects']], $reg_object[$_GET['myObjects']]);
        $IndexView = $myGui -> IndexView();

                            $content = $myGui -> NewView().$IndexView.$myGui -> MyModal();                            
        $array['content'] = $content; 

    }

    $array['title'] = '';
    $array['invoice_modal'] = Gui::modal('<div id="InvoiceArea"></div>', 'modal-lg', '', 'invoice-modal');
    $array['clients_modal'] = Gui::modal('<div id="ClientsArea"></div>', 'modal-lg', '', 'clients-modal');
    $array['edit_modal'] = Gui::modal('<div id="EditArea"></div>', 'modal-lg', '', 'edit-modal');
    $array['document_modal'] = Gui::modal('<div id="DocumentArea"></div>', 'modal-lg', '', 'document-modal');
    

    header('Content-type: text/html; charset=utf-8');

                                $text = Stringf::k2v(Filef::Read('./_Gui/page.html'), $array);
    $mdblObj = new MyDataByLink($text);

    print  $mdblObj -> text;

    