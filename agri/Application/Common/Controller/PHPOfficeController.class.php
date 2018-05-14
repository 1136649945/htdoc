<?php
namespace Common\Controller;

use Think\Controller;
use PhpOffice\PhpWord\IOFactory;
/*
 * Zend/Escaper：https://github.com/zendframework/zend-escaper

Zend/Stdlib：https://github.com/zendframework/zend-stdlib

Zend/Validator：https://github.com/zendframework/zend-validator
 */
class PHPOfficeController extends Controller
{
    //word转成html
    public function word2html($docpath,$filename)
    {
        if(is_file($docpath)){
            $exts = pathinfo($docpath)['extension'];
            if(in_array_case($exts, array('doc','docx'))){
                import("Org.Util.PHPWord");
                $word = IOFactory::load($docpath);
                $xmlWriter = IOFactory::createWriter($word, "HTML");
                $xmlWriter->save($filename);
            }else{
                throw_exception($docpath."\n"."不支持的文件格式！"."\n只支持Word2007后缀docx");
            }
        }else{
          throw_exception($docpath."\n"."文件不存在！");
        }
    }
}

