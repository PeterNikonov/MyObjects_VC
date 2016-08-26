<?php
namespace MyObjects {

  class Autoloader
  {
    const debug = 0;

    public function __construct(){}

    public static function autoload($file)
    {
      $file = str_replace('\\', '/', $file);
      $path = './classes';
      $filepath = './classes/' . $file . '.php';

      if (file_exists($filepath))
      {
        if(Autoloader::debug) Autoloader::StPutFile(('подключили ' .$filepath));
        require_once($filepath);
        
      }
      else
      { 
        $flag = true;
        if(Autoloader::debug) Autoloader::StPutFile(('начинаем рекурсивный поиск'));
        Autoloader::recursive_autoload($file, $path, $flag);
      }
    }

    public static function recursive_autoload($file, $path, $flag)
    {
      if (FALSE !== ($handle = opendir($path)) && $flag)
      {
        while (FAlSE !== ($dir = readdir($handle)) && $flag)
        {
          
          if (strpos($dir, '.') === FALSE)
          {
            $path2 = $path .'/' . $dir;
            $filepath = $path2 . '/' . $file . '.php';
            if(Autoloader::debug) Autoloader::StPutFile(('ищем файл <b>' .$file .'</b> in ' .$filepath));
            if (file_exists($filepath))
            {
              if(Autoloader::debug) Autoloader::StPutFile(('подключили ' .$filepath ));
              $flag = FALSE;
              require_once($filepath);
              break;
            }
            Autoloader::recursive_autoload($file, $path2, $flag); 
          }
        }
        closedir($handle);
      }
    }
  
    private static function StPutFile($data)
    {
            echo $data;
    }
    
  }
  
  \spl_autoload_register('MyObjects\Autoloader::autoload');

}
?>