<?php

class MyRelation {

    private $name; 
    private $mode; // режим
    private $value; // значение

    // - - -
    public function __construct( $name, $value, $mode = 1 ) {

        $this -> value = $value;
        $this -> name = $name;
        $this -> mode = $mode;

    }

    // ввод нового значения
    private function NewValue() {
        return '<input type="text" name="'.$this->name.'" class="form-control" placeholder="Ввод нового значения">';
    }

    // выбор существующего значения
    private function SelectValue( PDO $pdo ) {

                              // получим варианты
                              $query = new MyQuery($this->name);
        $stmt = $pdo -> query($query -> getList());
        $vars = $stmt -> fetchAll();

        $select = ''; $options = '';

        if($vars) {

            foreach($vars as $obj) {
                $selected = ($obj -> id == $this-> value) ? "selected" : "";
                $options.='<option '.$selected.' value="'.$obj -> id.'">'.$obj -> name.'</option>';
            }

            $select.='<select name="'.$this->name.'" class="form-control">'.$options.'</select>';

        }

        return $select;
    }

    public function View() {

        global $pdo;

        $new_active = '';
        $sel_active = '';

        if( $this-> mode ==0 ) { $input = $this -> NewValue(); $new_active = 'btn-info'; }
                         else { $input = $this -> SelectValue($pdo); $sel_active = 'btn-info'; }
 
$path = '<div class="input-group" id="ue:'.$this->name.'" style="width: 100%;">
  <div class="input-group-btn">
    <span onclick="ue_reload(\''.$this->name.'\', 0);" class="btn '.$new_active.'" data-toggle="tooltip" data-placement="bottom" title="Ввод нового значения">
    <i class="glyphicon glyphicon-plus"></i>
    </span>
    <span onclick="ue_reload(\''.$this->name.'\', 1);" class="btn '.$sel_active.'" data-toggle="tooltip" data-placement="bottom" title="Выбрать существующее значение">
    <i class="glyphicon glyphicon-triangle-bottom"></i>
    </span>
  </div>
  '.$input.'
</div>';

        return $path;

    }

    public function View_RelationSelectOnly() {

        global $pdo;

        $input = $this -> SelectValue($pdo);

$path = '
  '.$input.'
';

        return $path;

    }
}
