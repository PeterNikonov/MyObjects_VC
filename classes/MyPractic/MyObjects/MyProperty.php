<?php


class MyProperty  {

    public $name; // column of property
    public $type; // type of property
    public $print_name; // GUI string name of property
    public $extras; // special command for this property

    public function __construct( $name, $type, $print_name, $extras ) {

        $this -> name = $name;
        $this -> type = $type;
        $this -> print_name = $print_name;
        $this -> extras = $extras;

    }

    // вывести поле на экран
    public function PrintField( MyObject $context ) {

        return new MyInput( $this, $context -> getValue( $this -> name ));

        }

    }