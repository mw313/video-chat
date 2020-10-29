<?php

class DisplayTools{
    static public function view($path, $variables, $display = true){
        ob_start();
            foreach($variables as $key=>$val){
                $$key = $val;
            }
            include_once($path);
        $result = ob_get_clean();
        
        if($display)
            echo $result;
        else
            return $result;
    }
}