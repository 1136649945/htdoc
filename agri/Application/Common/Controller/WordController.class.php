<?php
namespace Common\Controller;

use Think\Controller;
/*
 * Zend/Escaper：https://github.com/zendframework/zend-escaper

Zend/Stdlib：https://github.com/zendframework/zend-stdlib

Zend/Validator：https://github.com/zendframework/zend-validator
 */
class WordController extends Controller
{
    //word转成html
    public function word2html($docpath,$fileName)
    {
        import("Org.Util.PHPWord");
        if(is_file($docpath)){
            $phpWord = new \PHPWord();
            $phpWord->setDefaultFontName($docpath);
//             $doc = IOFactory::load($docpath);
//             $phpWord->setDocumentProperties($doc);
            $phpWord->save($fileName,"HTML");
        }
    }
    
}

