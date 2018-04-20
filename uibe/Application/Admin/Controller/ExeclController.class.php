<?php
namespace Admin\Controller;

class ExeclController extends \Common\Controller\ExeclController{
    public function index(){
        $this->display();
    }
    public function import(){
        if (IS_POST){
           var_dump($this->read());
           exit();
        }
    }
    public function export(){
        if(IS_POST){
            $data = array(
                array(NULL, 2010, 2011, 2012),
                array('Q1',   12,   15,   21),
                array('Q2',   56,   73,   86),
                array('Q3',   52,   61,   69),
                array('Q4',   30,   32,    0),
            );
            $this->write($data);
        }
    }
}

