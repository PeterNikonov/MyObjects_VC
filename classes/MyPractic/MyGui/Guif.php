<?php



class Guif {
    
    public static function FormGroup($name, $field) {

        return '<div class="form-group">
                    <label for="" class="col-xs-4 control-label">'.$name.'</label>
                    <div class="col-xs-8">
                      '.$field.'
                      </div>
                      </div>';

    }
    
    public static function FormGroupShort($name, $field) {

        return '<p>
                      '.$field.'
                </p>';

    }

    // выбрать значение из массива
    public static function SelectFromObject($name, $arrayObject, $js = '') {

        $ret = '';

        if($arrayObject) {
            
            $ret.= '<select name="'.$name.'" class="form-control" '.$js.'>';
            
            foreach ($arrayObject as $a) {
                $ret.='<option value="'.$a -> id.'">'.$a -> name.'</option>';
            }

            $ret.= '</select>';
            
        }
        
        return $ret;

    }
    
    public function __toString() {

    }

}
