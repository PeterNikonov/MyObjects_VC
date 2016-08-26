<?php


class MyObject {
    
    public $values; // row from database as object
    public $properties;

    // загрузить в объект свойства и значения
    public function __construct($values = false, $properties) {

        $this -> properties = $properties;
        $this -> values = $values;

    }

    public function getValue($name) {

        return $this -> values -> $name;

    }

    // получить форму
    public function PrintForm() {

        if($this -> properties) {

            foreach($this -> properties as $property) {

                $path.= Guif::FormGroup($property -> print_name, $property -> PrintField($this));

            }
        }

        if($this -> values) { $btnString = 'Обновить'; $btnClass = 'btn-primary'; $legend = 'Обновить существующую запись'; } else {  $btnString = 'Создать'; $legend = 'Создать новую запись'; $btnClass = 'btn-info'; }
 
        return '<div class="form-horizontal"><legend style="font-size: 14px; color: #E5E5E5;">'.$legend.'</legend>'.$path.''
                . Guif::FormGroup( '' , '<div class="row"><div class="col-xs-6"><button type="submit" class="btn '.$btnClass.' btn-block">'.$btnString.'</button></div><div class="col-xs-6"> <span class="btn btn-default btn-block" data-dismiss="modal">Отмена</button></div></div> ')
                . '</div>';

    }
    
}
