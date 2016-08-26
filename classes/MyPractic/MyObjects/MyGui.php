<?php

// алфавитный указатель объектов
class MyGui {

    // описание объекта и свойств
    private $table;
    private $context;
    private $modname;

    // получить описание объекта
    public function __construct($table, $context, $modname = '') {

        $this -> table = $table;
        $this -> context = $context;
        $this -> modname = $modname;

    }

    // контекстный модаль в который грузим форму
    public function MyModal() {

$path = '
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="myModalLabel">'.$this->modname.'</h5>
      </div>
      <div class="modal-body" id="myForm">
        &nbsp;
      </div>
    </div>
  </div>
</div>';

    // если есть потребность уведомления
    if(isset($_GET['message'])) {

        $message[1] = 'Запись добавлена';
        $message[2] = 'Запись отредактирована';
        $message[3] = 'Запись удалена';
        
        $message_modal = '
<div id="myMessageModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!--<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">'.$this-> modname.'</h4>
      </div>-->
      <div class="modal-body" align="center">
        <p>'.$message[$_GET['message']].'</p>
            <span class="btn btn-info" data-dismiss="modal">OK</span>
      </div>
    </div>
  </div>
</div>

<script> $(\'#myMessageModal\').modal(\'show\'); </script>
';

    } else { $message_modal = ''; }

        return $path.$message_modal;

    }

    // инициализация компонента создания записи
    public function NewView() {
        
        global $reg_object;
        
        $path = '<div class="row">'
                . '<div class="col-sm-4"><h4>'.$reg_object[$_GET['myObjects']].'</h4></div>'
                . '<div class="col-sm-8">'
                . '<span class="btn btn-lg btn-info" onclick="myCreate(\''.$this -> table.'\', \'\');"><i class="glyphicon glyphicon-plus-sign"></i> &nbsp; Создать</span>'
                . '</div>'
                . '</div>'
              . '<hr>';   

        return $path;

    }

    // получить указатель по родительскому признку
    public function ParentIndexView($parent, $parent_template = '', $child_template = '', $childOrderBy = '', $parentOrderBy = '') {

        if($parent_template == '') { $parent_template = '<h4>%name% <span class="text-info" onclick="myCreate(\''.$this -> table.'\', \''.$parent.':%id%\');"><i class="glyphicon glyphicon-plus-sign"></i></span> </h4>'; }
        if($child_template == '') { $child_template = '<li>%name% %update_init% %delete_init%'; }

        $node_template = '%parent% <ul class="child_list">%child%</ul>';

                  $objects_array = $this -> GetParentIndex($parent, $childOrderBy, $parentOrderBy);
        if(!empty($objects_array)) {

            foreach($objects_array as $node) {

                $parent = $node[0]; $child = $node[1];

                $parent_array = (array) $parent;
                $parent_string = Stringf::k2v($parent_template, $parent_array);

                $child_list = '';

                foreach($child as $object) {
                    // преобразуем в массив
                    $array = (array) $object;
                    // добавим   фичи
                    $array['update_init'] = '<span class="btn btn-xs text-primary" onclick="myUpdate(\''.$this -> table.'\', '.$object -> id.');"><i class="glyphicon glyphicon-pencil"></i></span>';
                    $array['delete_init'] = '<span class="btn btn-xs text-danger" onclick="myDelete(\''.$this -> table.'\', '.$object -> id.');"><i class="glyphicon glyphicon-remove"></i></span>';

                    $child_list.= Stringf::k2v($child_template, $array);

                }

                if(!empty($child)) {
                    $content[]['content'] = Stringf::k2v( $node_template, array('parent' => $parent_string, 'child' => $child_list)); 
                }
            }
        }

        return Stringf::column_content($content);

    }

    protected function GetParentIndex($parent, $childOrderBy = '', $parentOrderBy = '', PDO $pdo = null) {

        global $pdo;

        $objects_assoc = array();
        $undefined = new stdClass;

        $undefined -> id = 0;
        $undefined -> name = '<small class="text-info"><strong>Связь не найдена</strong></small>';

        // получим данные для переданного ID
        $query_parent = new MyQuery($parent);
        $query_parent ->SetOrderBy($parentOrderBy);

        $query_child = new MyQuery($this -> table);
        $query_child -> SetOrderBy($childOrderBy);

                      $stmt = $pdo -> query( $query_parent -> getList());
           $parents = $stmt -> fetchAll();
           $parents = array_merge(array($undefined), $parents);

        if($parents) {

            foreach( $parents as $parent_obj) {
                                  // построим асс. массив - родитель -> записи
                                  $stmt = $pdo -> query( $query_child -> getListByClause("$parent:".$parent_obj->id.""));
                       $objects = $stmt -> fetchAll();

                       $objects_assoc[] = array( $parent_obj, $objects );   

            }
        }

        return $objects_assoc;

    }

    // получает на вход сформированные данные
    public function IndexView( $letter_template = '', $row_template = '') {

        if($letter_template == '') {

           $letter_template = '<table class="table =" style="width:100%;"><tr><td class="letter"><big>%letter%</big></td>'
                             .'<td class="letter_list"><table class="table table-hover table-condensed">%letter_list%</table></td></tr></table>';

        }

        if($row_template == '') {
           $row_template = '<tr><td width="80%">%name%</td><td>%update_init%</td> <td>%delete_init%</td></tr>';
        }

        $content = array();

                  $objects_array = $this -> GetIndex();
        if(!empty($objects_array)) {

            foreach($objects_array as $letter => $objects) {

                $letter_list = '';

                foreach($objects as $object) {
                    // преобразуем в массив
                    $array = (array) $object;
                    // добавим   фичи
                    $array['update_init'] = '<span class="btn btn-xs text-info" onclick="myUpdate(\''.$this -> table.'\', '.$object -> id.');"><i class="glyphicon glyphicon-pencil"></i></span>';
                    $array['delete_init'] = '<span class="btn btn-xs text-warning" onclick="myDelete(\''.$this -> table.'\', '.$object -> id.');"><i class="glyphicon glyphicon-remove"></i></span>';

                    $letter_list.= Stringf::k2v($row_template, $array);

                }
                $content[]['content'] = Stringf::k2v( $letter_template, array('letter' => $letter, 'letter_list' => $letter_list));
            }
        }

        return Stringf::column_content($content);
    }

    // построить алфавитный указатель
    protected function GetIndex($fleid = 'name', PDO $pdo = null ) {

        global $pdo;

        $objects_assoc = array();

        // получим данные для переданного ID
        $query = new MyQuery($this -> table);

                   $stmt = $pdo -> query( $query -> getFirstLetters());
        $letters = $stmt -> fetchAll();
     if($letters) {

            foreach( $letters as $row) {

                // построим асс. массив - буква -> записи
                if(trim($row -> letter)!=='') {

                               $stmt = $pdo -> query( $query ->  getListByLike($fleid." LIKE '".$row -> letter."%' "));
                    $objects = $stmt -> fetchAll();
                    $objects_assoc[$row -> letter] = $objects;

                }
            }
        }

        return $objects_assoc;

    }

    // форма на создание
    public function CreateForm($data = false) {

    // создадим сущность
    $myObj = new MyObject( $data, $this -> context);
    
    // по полной программе - форма, сабмит и т.д.
    $path = '<form action="?action=insert&table='.$this -> table.'" method="POST">'.$myObj -> PrintForm();
    $path.= '</form>';

    return $path;

    }

    // форма на редактирование
    public function UpdateForm($id = false, PDO $pdo = null) {

        global $pdo;

        if($id) {

            // получим данные для переданного ID
            $query = new MyQuery($this -> table);

            $stmt = $pdo -> query( $query -> getListById($id));
            $data = $stmt -> fetch();

                    // создадим сущность
                    $myObj = new MyObject( $data, $this -> context);
            $form = $myObj -> PrintForm();

        }

    // по полной программе - форма, сабмит и т.д.
    $path = '<form action="?action=update&table='.$this -> table.'&id='.$data -> id.'" method="POST">'.$form;
    $path.= '</form>';

    return $path;

    }

    // форма на удаление - подтверждение
    public function DeleteForm($id, PDO $pdo = null) {
        
    global $pdo;

        if($id) {

            // получим данные для переданного ID
            $query = new MyQuery($this -> table);

            $stmt = $pdo -> query( $query -> getListById($id));
            $data = $stmt -> fetch();

            $path = '<h4>'.@$data -> lastname.' '.$data -> name.' '.@$data -> patronymic.'</h4>'
                    . '<p>Вы действительно хотите удалить эту запись?</p> '
                    . '<br>'
                    . '<div class="row"><div class="col-xs-6"><a href="?action=delete&table='.$this->table.'&id='.$data -> id.'" class="btn btn-danger btn-block">Удалить</a></div><div class="col-xs-6"> <span class="btn btn-default btn-block" data-dismiss="modal">Отмена</button></div></div>'
                    . ''
                    . '';

        } else { 
          $path = '<p>Удаление невозможно</p>';
        }

        return $path;

    }
}
