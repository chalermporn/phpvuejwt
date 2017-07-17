<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/libs/RestServer.php';
require_once __DIR__.'/libs/config.php';
require_once __DIR__.'/libs/models.php';

//------------- INIT----------------------------------------
if (!function_exists( 'implodeKV' ) && ! function_exists('consolelog') ) {
  function implodeKV($glueKV, $gluePair, $KVarray)  {
    if( is_object($KVarray) ) {
      $KVarray = json_decode(json_encode($KVarray),TRUE);
    }
    $t = array();
    foreach($KVarray as $key=>$val) {
      if(is_array($val)){
        $val = implodeKV(':',',',$val);
      }else if( is_object($val)){
        $val = json_decode(json_encode($val),TRUE);
        $val = implodeKV(':',',',$val);
      }

      if(is_int($key)){
        $t[] = $val;
      } else {
        $t[] = $key . $glueKV . $val;
      }
    }
    return implode($gluePair, $t);
  }
  function consolelog($status = 200)  {
    $lists = func_get_args();
    $status = '';
    $status = implodeKV( ':' , ' ' , $lists);
    if(isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"])){
      $raddr =$_SERVER["REMOTE_ADDR"];
    } else {
      $raddr = '127.0.0.1';
    }
    if(isset($_SERVER["REMOTE_PORT"]) && !empty($_SERVER["REMOTE_PORT"])){
      $rport = $_SERVER["REMOTE_PORT"];
    } else {
      $rport = '8000';
    }

    if(isset($_SERVER["REQUEST_URI"]) && !empty($_SERVER["REQUEST_URI"])){
      $ruri = $_SERVER["REQUEST_URI"];
    } else {
      $ruri = '/console';
    }
    file_put_contents("php://stdout",
      sprintf("[%s] %s:%s [%s]:%s \n",
        date("D M j H:i:s Y"),
        $raddr,$rport,
        $status,
        $ruri
        )
      );
  }
} // end-of-check funtion exist

if (!function_exists('logAccess')) {
    function logAccess($status = 200) {
      file_put_contents("php://stdout", sprintf("[%s] %s:%s [%s]: %s\n",
        date("D M j H:i:s Y"), $_SERVER["REMOTE_ADDR"],
        $_SERVER["REMOTE_PORT"], $status, $_SERVER["REQUEST_URI"]));
    }
}
//------------- INIT----------------------------------------
// require_once __DIR__.'/English.php';

require __DIR__.'/controllers/TestController.php';
require __DIR__.'/controllers/AuthController.php';
require __DIR__.'/controllers/QuestionController.php';

$server = new \Jacwright\RestServer\RestServer('debug');
$server->addClass('TestController');
$server->addClass('AuthController','/api/v1/auth');
$server->addClass('QuestionController','/api/v1/q');
$server->handle();
