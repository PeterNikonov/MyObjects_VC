<?php

class MyInput {

    private $context;
    private $value;

    public function __construct( MyProperty $context, $value ) {
        $this -> context = $context; $this -> value = $value;
    }

    protected function TextArea() {
        return '<textarea name="'.$this -> context -> name.'" class="form-control">'.$this -> value.'</textarea>';
    }

    protected function Text() {
        return '<input type="text" autocomplete="off" name="'.$this -> context -> name.'" value="'.$this -> value.'" class="form-control">';
    }

    protected function TextColor() {
        return ''
                . ''
                . '<input type="text" autocomplete="off" name="'.$this -> context -> name.'" value="'.$this -> value.'" class="form-control color">'
                . '<p>Выберите нужный оттенок, после чего кликните за пределами области выбора цвета. Укажите наименование цвета.</p>'
                . ''
                . ''
                . '';
    }

    // связь
    protected function Relation() {

        $input = new MyRelation($this -> context -> name, $this -> value);
        return (string) $input -> View();

    }

    // связь
    protected function RelationSelectOnly() {

        $input = new MyRelation($this -> context -> name, $this -> value);
        return (string) $input -> View_RelationSelectOnly();
    }

    // вывести форму
    public function __toString() {

        switch ($this -> context -> type) {

            case 'Text': return $this->Text(); break;
            case 'TextArea':  return $this -> TextArea(); break;
            case 'Relation':  return $this -> Relation(); break;
            case 'RelationSelectOnly':  return $this -> RelationSelectOnly(); break;
            case 'TextColor': return $this->TextColor(); break;

        }
    }
            
}

?>