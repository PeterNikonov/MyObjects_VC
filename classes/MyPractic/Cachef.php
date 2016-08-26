<?php

    /*
    кеширование данных в ФС
    */

class Cachef {

    static function read($fileName) 
    {
                     $fileName = './_cache/'.$fileName;
      if(file_exists($fileName)) 
      {

        $handle = fopen($fileName, 'rb');

               $variable = fread($handle, filesize($fileName)); fclose($handle);
        return $variable;

      } else { return FALSE; }
    }

    static function write($variable, $fileName) 
    {

    @mkdir('./_cache', 0777);
    
    
	                         $fileName = './_cache/'.$fileName;
	         $handle = fopen($fileName, 'w+');
          fwrite($handle, $variable);
	        fclose($handle);

    }

    static function delete($fileName) 
    {
                        $fileName = './_cache/'.$fileName;
	        @unlink($fileName);
    }
}


?>