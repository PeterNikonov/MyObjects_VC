<?php

class Datef {

    public static $im = '-,Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь';
    public static $rm = '-,января,февраля,марта,апреля,мая,июня,июля,августа,сентября,октября,ноября,декабря';
    public static $wd = array('*' => 'Любой', 'Mon' => 'Пн', 'Tue' => 'Вт', 'Wed' => 'Ср', 'Thu' => 'Чт', 'Fri' => 'Пт', 'Sat' => 'Сб', 'Sun' => 'Вс');

    public static function rus_wd($wd) {      
        return self::$wd[$wd];
    }

    public static function rus_im($m) {
               $month = explode(',',self::$im);
        return $month[(int)$m];
    }

    public static function rus_rm($m) {
               $month = explode(',',self::$rm);
        return $month[(int)$m];
    }

    public static function date_explode($date) {
        return explode('-', $date);
    }

    // вычисление даты  - интервал, количество интервалов, в будущем или в прошлом
    public static function date_to_period($date, $where, $period_var, $interval_var) {
        // разбиваем дату
        $dt = self::date_explode($date);
        $dt_y = $dt[0];
        $dt_m = $dt[1];
        $dt_d = $dt[2];

        if ($where == '+') {
            switch ($interval_var) {
                case 'm': $mktime_val = @mktime(0, 0, 0, $dt_m + $period_var, $dt_d, $dt_y);
                    break;
                case 'd': $mktime_val = mktime(0, 0, 0, $dt_m, $dt_d + $period_var, $dt_y);
                    break;
                case 'y': $mktime_val = mktime(0, 0, 0, $dt_m, $dt_d, $dt_y + $period_var);
                    break;
            }
        }
        if ($where == '-') {
            switch ($interval_var) {
                case 'm': $mktime_val = mktime(0, 0, 0, $dt_m - $period_var, $dt_d, $dt_y);
                    break;
                case 'd': $mktime_val = mktime(0, 0, 0, $dt_m, $dt_d - $period_var, $dt_y);
                    break;
                case 'y': $mktime_val = mktime(0, 0, 0, $dt_m, $dt_d, $dt_y - $period_var);
                    break;
            }
        }

        return date("Y-m-d", $mktime_val);
    }

    public static function RU_date($date = '', $y = false) {

        if (trim($date) !== '') {

            $m = explode(',', self::$rm);
            $e = self::date_explode($date);

            $year = ($y) ? ' '.$e[0] : '';

            return '' . $e[2] . ' ' . $m[(INT) $e[1]].$year;
        }
    }
    
    public static function RU_dateTime($datetime = '', $y = false) {

        if (trim($datetime) !== '') {

            $m = explode(',', self::$rm);
            $t = explode(' ', $datetime);
                                    
                                    $date = $t[0]; $time = $t[1];
                                    $timeExp = explode(':', $time); $time = $timeExp[0].':'.$timeExp[1];
            $e = self::date_explode($date);

            $year = ($y) ? ' '.$e[0] : '';

            return '' . $e[2] . ' ' . $m[(INT) $e[1]].$year.' '.$time;
        }
    }
    
    public static function RU_dateShortMonth($date = '', $y = false) {

        if (trim($date) !== '') {

            $m = explode(',', self::$rm);
            $e = self::date_explode($date);

            $year = ($y) ? ' '.$e[0] : '';
            
            $month = mb_substr(trim($m[(INT) $e[1]]), 0, 3);
            return '' . $e[2] . ' ' . $month.$year;
        }
    }

    public static function weekday($date = '') {

        if (trim($date) !== '') {
            $e = self::date_explode($date);
            return date("D", mktime(0, 0, 0, $e[1], $e[2], $e[0]));
        }
    }

    public static function Ymd($string) {

        if (Stringf::in_str('-', $string)) {
            $e = self::date_explode($string);
            return sprintf("%04d-%02d-%02d", $e[0], $e[1], $e[2]);
        } else {
            return date('Y-m-d');
        }
    }

    public static function dmY2Ymd($masked_str) {
        list($d, $m, $y) = explode('/', $masked_str);

        $ret = sprintf("%04d-%02d-%02d", $y, $m, $d);
        return $ret;
    }

    public static function Ymd2dmY($masked_str) {
        list($y, $m, $d) = explode('-', $masked_str);

        $ret = sprintf("%02d/%02d/%04d", $d, $m, $y);
        return $ret;
    }

    public static function month_dayCount($y, $m) {
        return date("t", mktime(0, 0, 0, $m, 1, $y));
    }

    public static function dateInterval($start, $end = '') {
        $start = strtotime($start);
        $end = empty($end) ? time() : strtotime($end);

        for ($d = $start; $d <= $end; $d = strtotime('tomorrow', $d))
            $interval[] = date('Y-m-d', $d);

        return $interval;
    }
    
    // округлить число до ближайшего кратного 
  public static function roundMinutes($m, $r) { for($i = $m; $m > 0; $i--) { if(!($i%$r)) { return $i; break; } } }
  // числовая метка обозначения времени 
  public static function time_to_int($time) { list($h, $m) = explode(':', $time); return (@$h*12)+(@$m/5); }
  // добавить к времени количество минут
  public static function time_add_min($time, $min) {

  list($h, $m) = explode(':', $time);

  $h = ($h + floor(($m + $min) / 60)) % 24;
  $m = ($m + $min) % 60;

  return str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);

  }

  // массив по предварительно установленных часов
  public static function periodArray($st, $fn, $interval, $edge_st = true, $edge_fn = true) {

  $st_int = Datef::time_to_int($st);
  $fn_int = Datef::time_to_int($fn);

  if($edge_st) { $arr[$st_int] = $st; }

    while ($st_int < $fn_int) {

      $st = Datef::time_add_min($st, $interval);                              
      $st_int = Datef::time_to_int($st);

      if($st_int !== $fn_int) $arr[$st_int] = $st;

    }

    if($edge_fn) { $arr[$fn_int] = $fn; }

    return $arr;

    }

}

?>