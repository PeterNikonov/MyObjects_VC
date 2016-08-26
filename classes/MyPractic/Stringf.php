<?php

class Stringf {
  
  public static function create_pswd() {

    $a = explode(',','Q,W,R,Y,U,S,D,F,G,J,L,Z,V,N');
    // смешанное содержание блока
    $s = array();

    $s[] = $a[rand(0, count($a))];
    $s[] = $a[rand(0, count($a))];
    $s[] = $a[rand(0, count($a))];
    $s[] = rand(2, 9);
    $s[] = rand(2, 9);
    $s[] = rand(2, 9);

    shuffle($s);

           $p = implode('',$s);
    return $p;

  }
    
  // вставить значения из массива в строку по ключу
  public static function k2v($string, $array)
  {
    if($array && is_array($array))
    {
      foreach($array as $k => $v) { $string = str_replace("%".$k."%", "".$v."", $string); }

      return $string;  
    } 
  }

  // синоним strrpos
  public static function in_str($needle, $haystack)
  {
     $pos = strrpos($haystack, $needle);
  if($pos === false) { return false;  } else { return TRUE; }

  }
  
  // вспомогательный для мэйн контент - делит массив на несколько - для каждой строки
  public static function data_span_arr($array, $col)
  { if($array && $col)
    { $count=count($array); $i=0; $a=0; 
      while($i < $count)
      { $ci=$i+$col; $ii=$i;
        while($ii <= $ci)
        { if(isset($array[$ii])) { $an_array[$a]=$ci; } $ii++;
        } $i=$ci; $i++; $a++;
      } $new_na[0]=0;
      foreach($an_array as $k=>$v) { $new_key=$k+1; $new_na[$new_key]=$v+1; }
      return $new_na;
    }
  }

  // мастер создания табличного вывода (контент в несколько столбцов)
  public static function column_content($content, $col = 2, $template_string = '<div class="col-md-4 col-sm-6">%content%</div>') 
  {

  $row = '';

  # $content - массив содержания 
  # $col - количество столбцов, (счет от нуля).
  # $template_string - шаблон

    if($col > 0)
    { $an_array = self::data_span_arr($content, $col);
    } $ci = 0;
      if($an_array)
      { 
        foreach($an_array as $k => $v)
        { 
          $row.='<div class="row">'; 
          
          $num=@$an_array[$k]+$col; 
          
          $vi=$v; 
          $one_cell=''; 
          $ci++;
          
          while($vi<=$num)
          { $one_cell.= self::k2v($template_string, @$content[$vi]); $vi++;
          } $row.=$one_cell.'</div>';
        }
        
      } else {
        if($content)
        { foreach($content as $key=>$val) { $row.='<div class="row margin-xs">'. self::k2v($template_string, $content[$vi]).'</div><br /><br /><br /><br />'; $ci++; } }
    } return $row;
  }

    // динамический алфавитный указатель
    public static function firstLetters($array, $key = 'name') {

        $letters = array();

        if($array) {
            foreach($array as $k => $v) {
                           $letter = mb_strtoupper( mb_substr ( trim($v -> $key) , 0, 1));
              $letters[] = $letter;
        }
    }
        $letters = array_unique($letters);

        $collator = new Collator('ru_RU');
        $collator -> sort($letters);

       return $letters;
    }

    // привязка строк по признаку - первая буква
    public static function letterAssoc($index, $array, $key = 'name') {
        foreach ($index as $letter) {
            foreach($array as $k => $v) {
                if(mb_strtoupper(mb_substr(trim($v -> $key), 0, 1)) == $letter) {
                    $sorted[$letter][] = $v;
                    unset($array[$k]);
                }
            }
        }
        return $sorted;
    }

    // указатель интервалов
    public static function DateIndex($array, $key = 'date') {
        
        $index = array();

        if($array) {
            foreach($array as $k => $v) {

                $date = Datef::date_explode($v -> $key);
                $index[] = $date['0'].':'.$date['1'];
        }
    }
               $index = array_unique($index);
        return $index;

    }
    
    // привязать элементы массива к существующим интервалам даты
    public static function DateAssoc($idnex, $array, $key = 'date') {

        foreach ($index as $date) {
            foreach($array as $k => $v) {

                              $temp_date = Datef::date_explode($v -> $key);
                $check_date = $temp_date['0'].':'.$temp_date['0'];

                if($date == $check_date) {
                    $sorted[$date][] = $v;
                    unset($array[$k]);
                }
            }
        }

        return $sorted;

    }
}

?>