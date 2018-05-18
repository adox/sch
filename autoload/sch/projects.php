<?php
namespace Sch;

class Projects{
    private $arr = array();

    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Projects();
        }
        return $inst;
    }

    public function add( $p ){
        $this->arr[] = $p;
    }

    public function getProjects(){
        return $this->arr;
    }
}
