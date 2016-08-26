<?php

/**
 * 
 *
 * принимает вывод, находит в нем шаблоны-ссылки, получает по ним значения из бд, заменяет на месте все соответствия.
 */
class MyDataByLink {

  public $text;

  public function __construct($text)
  {

/* - 
     а - определяем содержащиеся в тексте ссылки на ресурсы бд
     б - получаем контекст ссылок на ресурсы бд содержащихся в тексте
     в - меняем ссылки на ключи
   -- 
     a - из ссылок формируем запросы к бд и получаем результат
     б - меняем ссылки на полученные значения в тексте
*/
            $pl = $this -> ParseLink($text);
            $rk = $this -> ReplaceKey($text);
  if(!empty($rk))
  {
                         $text = $this -> LinkToKey($text, $rk);
    $text = Stringf::k2v($text, $this -> ValuesToLink($pl));
  } 
  
  $this -> text = $text;

  }

  // заменить ссылки на ассоциации в переданной строке
  private function LinkToKey($string, $array)
  {
    if($array && is_array($array))
    {
      foreach($array as $k=>$v) { $string = str_replace("{".$k."}", "%".$v."%", $string); }
      return $string;  
    } 
  }

  // найти запросы к ресурсам бд в строке
  // вернуть ассоциации ключ ссылки -> (таблица-поле-идентификатор) 
  private function ParseLink($str)
  {

  $result = array();

  preg_match_all('/({\w*->\w*:\d*})/', $str, $array);

  if($array && is_array($array))
  {
    foreach($array[0] as $sign)
    {

                     $sign = str_replace(array('{','}'), '', $sign);
    $sign_hash = md5($sign);

    $e0 = explode('->', $sign);
    $e1 = explode(':', $sign);

    $t = $e0[0];
    $f = str_replace(array($t,'->'), '', $e1[0]);
    $i = $e1[1];

    $result[ $sign_hash ] = array($t,$f,$i);

    }
  }

  return $result;

  }
  
  // найти запросы к ресурсам бд в строке
  // вернуть ассоциации ссылка -> ключ
  private function ReplaceKey($str)
  {
  
  $replace = array();
    
  preg_match_all('/({\w*->\w*:\d*})/', $str, $array);
  
  if($array && is_array($array))
  {
    foreach($array[0] as $sign)
    {

    $sign = str_replace(array('{','}'), '', $sign);
    $sign_hash = md5($sign);

    $replace[$sign] = $sign_hash;

    }
  }

  return $replace;

  }

  // сформировать SQL запрос для ссылки
  private function QueryToLink($params)
  {

    global $pdo;

    $q = "SELECT ".$params[1]." FROM ".prefix."_".$params[0]." WHERE id = ".$params[2]."";

                  $stmt = $pdo -> query($q);
           $res = $stmt -> fetch();

           if($res) {
               return $res -> $params[1];
           } else { return '<b>[?]</b>'; }
  }

  private function ValuesToLink($array)
  {
    $result = array();

    if($array)
    {
        foreach($array as $key => $params)
        {
        
        $result[$key] = $this -> QueryToLink($params);

        }
    }

    return $result;

    }

}
