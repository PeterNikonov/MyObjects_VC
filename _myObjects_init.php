<?php

    $reg_object['division'] = 'Филиал';
    $reg_object['profile'] = 'Направление';
    $reg_object['service'] = 'Услуга';

    $reg_property['division'][] = new MyProperty('name', 'Text', 'Наименование', null);
    $reg_property['profile'][] = new MyProperty('name', 'Text', 'Наименование', null);
    
    $reg_property['service'][] = new MyProperty('name', 'Text', 'Наименование', null);
    // дополнительные свойства - ссылки на строки в таблицах "филиал" и "направление"
    $reg_property['service'][] = new MyProperty('division', 'Relation', 'Филиал', null);
    $reg_property['service'][] = new MyProperty('profile', 'Relation', 'Направление', null);
    // - - -
    $reg_property['service'][] = new MyProperty('price', 'Text', 'Цена', null);
    