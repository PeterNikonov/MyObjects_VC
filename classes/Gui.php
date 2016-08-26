<?php

class Gui {

// реализация добавления специалиста
public static function modal($cnt, $size = '', $header = '', $modalClassName = 'addState-modal') {

$ret  = '<!-- Large modal -->
<div class="modal fade '.$modalClassName.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
<div class="modal-dialog '.$size.'">
    <div class="modal-content">
        '.$header.'
        <div class="modal-body" style="padding-top: 0;">
        '.$cnt.'
        </div>
    </div>
    </div>
</div>';

    return $ret;

}

public static function DefaultHeader() {

    $head = '
    <div class="modal-header">
        &nbsp;
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red;"><span aria-hidden="true">&times;</span></button>
    </div>';

    return $head;

}

    // листать месяц туда-сюда
    public static function MonthSelect($y, $m, $function = '', $areaId = '') {
    
        $fd = Datef::Ymd($y."-".$m."-01"); 
        
    $prev_exp = Datef::date_explode(Datef::date_to_period( $fd , '-', 1, 'm'));
    $next_exp = Datef::date_explode(Datef::date_to_period( $fd , '+', 1, 'm'));

    $prev_link = 'index.php?ajax='.$function.'&msY='.$prev_exp[0].'&msM='.$prev_exp[1].'';
    $next_link = 'index.php?ajax='.$function.'&msY='.$next_exp[0].'&msM='.$next_exp[1].'';

    $ret = '
<div class="btn-group" role="group">
<button class="btn btn-default" onclick="loadCnt(\''.$prev_link.'\', \''.$areaId.'\');">
<i class="glyphicon glyphicon-chevron-left color_primary"></i>
</button>
<button type="button" class="btn btn-default" style="width: 150px;">
    '.Datef::rus_im($m).'&nbsp;'.$y.'  
</button>
<button class="btn btn-default" onclick="loadCnt(\''.$next_link.'\', \''.$areaId.'\');">
<i class="glyphicon glyphicon-chevron-right color_primary"></i>
</button>
</div>';

    return $ret;

    }
    
    // листать месяц туда-сюда
    public static function MonthLink($y, $m, $link = '', $areaId = '') {

    $fd = Datef::Ymd($y."-".$m."-01"); 

    $prev_exp = Datef::date_explode(Datef::date_to_period( $fd , '-', 1, 'm'));
    $next_exp = Datef::date_explode(Datef::date_to_period( $fd , '+', 1, 'm'));

    $prev_link = 'index.php?'.$link.'&dsY='.$prev_exp[0].'&dsM='.$prev_exp[1].'';
    $next_link = 'index.php?'.$link.'&dsY='.$next_exp[0].'&dsM='.$next_exp[1].'';

    $ret = '
<div class="btn-group" role="group">
<a href="'.$prev_link.'" class="btn btn-default">
<i class="glyphicon glyphicon-chevron-left color_primary"></i>
</a>
<button type="button" class="btn btn-default" style="width: 150px;">
    '.Datef::rus_im($m).'&nbsp;'.$y.'  
</button>
<a href="'.$next_link.'" class="btn btn-default">
<i class="glyphicon glyphicon-chevron-right color_primary"></i>
</a>
</div>';

    return $ret;

    }   
}