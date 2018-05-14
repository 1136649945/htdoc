<?php
namespace Common\Controller;

use Think\Controller;

class ExeclController extends Controller
{
    // 导入
    public function read()
    {
        $data = array();
        $upload = new \Think\Upload(C('IMPORT_UPLOAD'), "Local", null);
        $info = $upload->upload();
        if (! $info) {
            $data['status'] = - 1;
            $data['info'] = $upload->getError();
            return $data;
        } else {
            $filename = C('IMPORT_UPLOAD')['rootPath'] . $info["file"]['savepath'] . $info["file"]['savename'];
            $exts = $info["file"]['ext'];
            // 导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
            import("Org.Util.PHPExcel");
            // 创建PHPExcel对象，注意，不能少了\
            $PHPExcel = new \PHPExcel();
            // 如果excel文件后缀名为.xls，导入这个类
            if ($exts == 'xls') {
                import("Org.Util.PHPExcel.Reader.Excel5");
                $PHPReader = new \PHPExcel_Reader_Excel5();
            } else 
                if ($exts == 'xlsx') {
                    import("Org.Util.PHPExcel.Reader.Excel2007");
                    $PHPReader = new \PHPExcel_Reader_Excel2007();
                } else 
                    if ($exts == 'csv') {
                        import("Org.Util.PHPExcel.Reader.CSV");
                        $PHPReader = new \PHPExcel_Reader_CSV();
                    }
            $execlAll = array();
            // 载入文件
            $PHPExcel = null;
            try {
                $PHPExcel = $PHPReader->load($filename);
            } catch (\Exception $e) {
                $data['status'] = - 1;
                $data['info'] = $e->getMessage();
                if (is_file($filename)) {
                    unlink($filename);
                }
                return $data;
            }
            // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
            $currentSheets = $PHPExcel->getAllSheets();
            foreach ($currentSheets as $currentSheet) {
                $execl = array();
                // 获取总列数
                $allColumn = $currentSheet->getHighestColumn();
                // 获取总行数
                $allRow = $currentSheet->getHighestRow();
                // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
                for ($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
                    // 从哪列开始，A表示第一列
                    for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
                        // 数据坐标
                        $address = $currentColumn . $currentRow;
                        // 读取到的数据，保存到数组$arr中
                        $execl[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
                    }
                }
                array_push($execlAll, $execl);
            }
            if (is_file($filename)) {
                unlink($filename);
            }
            $data['status'] = 1;
            $data['info'] = $execlAll;
            return $data;
        }
    }
    
    // 导出
    public function write($data,$filename="data.xls")
    {
       import("Org.Util.PHPExcel");
        $phpexcel = new \PHPExcel();
        $phpexcel->getProperties()
            ->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $phpexcel->getActiveSheet()->fromArray($data);
        $phpexcel->getActiveSheet()->setTitle('Sheet1');
        $phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objwriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
        $objwriter->save('php://output');
        exit();
    }
}

