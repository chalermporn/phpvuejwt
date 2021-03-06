<?php

use \Jacwright\RestServer\RestException;
use \Jacwright\RestServer\RestController as BaseController;


class SysController extends BaseController {
    
    public function getTest($id = null,$a=null,$b=null,$c=null) {
        $o = new stdClass();
        $o->url = $this->server->url;
        $o->method = $this->server->method;
        $o->params = $this->server->params;
        $o->tokenverify = $this->server->tokenverify();
        $o->data = 'tlen work';
        $o->format = $this->server->format;
        $o->jwt = $this->server->jwtobj;
        $o->role = $this->server->chk('a','t');
        $o->testdata = $this->server->testdata();
        if($o->jwt->status && $this->server->chk('a','t')){
            $o->status = 'success';
        } else{
            $o->status = 'unsuccess';
        }
        return $o;
    }


    private function  info($info=null){
        echo "<br/><center><b>API Server JWT v1.".((isset($info) && $info=='tlen') ? $info : null)." is WORK!</b></cener>";
    }

    public function getRoutes($info=null) {
        $this->info($info);
        if($this->server->mode == 'debug' || $info == 'tlen') {
            echo '<table><thead><tr><td><b>Route</b></td><td><b>Controller</b></td><td><b>Method</b></td><td><b>$args</b></td><td>null</td><td><b>$noAuth</b></td></tr></thead><tbody>';
            foreach ($this->server->routes() as $routekey => $routes) {
                echo '<tr><td colspan="6">--------------------------> '.$routekey.'-------------------------------------------------------------------------------</td></tr>';
                foreach ($routes as $key => $value) {
                    echo "<tr><td>$key</td><td>$value[0]</td><td>$value[1]</td><td><pre>".json_encode($value[2])."</pre></td><td>".json_encode($value[3])."</td><td>".json_encode($value[4])."</td></tr>";
                }
            }
            echo '</tbody></table>';
        }
        exit(0);
    }

    public function getServerinfo($info=null,$show=null) {
        $this->info($info);
        if($this->server->mode == 'debug' || $info == 'tlen'){
            dump($this);
            if($info=='tlen' && $show){
                phpinfo();
            }

        }
        exit(0);
    }


    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /
     * @noAuth
     */
    public function test()
    {

        echo "<br/><center><b>API Server JWT v1. is WORK!</b></cener>";
        require __DIR__.'/../home/magic.html';
        exit(0);
    }

    /**
     * Throws an error
     * 
     * @url GET /error
     */
    public function throwError() {
        throw new RestException(401, "Empty password not allowed");
    }
}