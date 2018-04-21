<?php
namespace Common\Controller;

use Think\Controller;
use PhpOffice\PhpWord\IOFactory;
/*
 * Zend/Escaper：https://github.com/zendframework/zend-escaper

Zend/Stdlib：https://github.com/zendframework/zend-stdlib

Zend/Validator：https://github.com/zendframework/zend-validator
 */
class WordController extends Controller
{
    //word转成html
    public function word2html($docpath,$name)
    {
        import("Org.Util.PHPWord");
        if(is_file($docpath)){
            $phpWord = IOFactory::load($docpath);
            $xmlWriter = IOFactory::createWriter($phpWord, "HTML");
            $xmlWriter->save($name);
        }
    }
    
}

