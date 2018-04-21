<?php
namespace Common\Controller;

use Think\Controller;
class WordController extends Controller
{
    //word转成html
    public function word2html($docpath,$name)
    {
        vendor('PhpWord');
        vendor('PhpWord.IOFactory');
        require_cache(VENDOR_PATH.'PhpWord/IOFactory');
        if(is_file($docpath)){
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($docpath);
        }
//         $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "HTML");
//         $xmlWriter->save($name);
    }
    
}

