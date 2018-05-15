<?php

namespace App\Controller;


class WeixinPayController extends AppController {
    
    /**
     * 预支付请求接口(POST)
     * @param string $openid    openid
     * @param string $body      商品简单描述
     * @param string $order_sn  订单编号
     * @param string $total_fee 金额
     * @return  json的数据
     */
    public function prepay(){
        $Money = M("Money");
        $data = $Money->create();
        if($data["total"]<0.9){
            $this->ajaxReturn(array("out_trade_no"=>-1,"info"=>"支付金额不能少于1元！"));
        }
        $id = $Money->add($data);
        if($id){
            $this->ajaxReturn(array("out_trade_no"=>$id,"info"=>"订单创建成功！"));
        }else{
            $this->ajaxReturn(array("out_trade_no"=>0,"info"=>"订单创建失败！"));
        }
    }
    /**
     * 支付请求接口(POST)
     * @param string $openid    openid
     * @param string $body      商品简单描述
     * @param string $order_sn  订单编号
     * @param string $total_fee 金额
     * @return  json的数据
     */
    public function pay(){
        $config = C("WEIXIN_PAY");
        $openid = I('post.openid');
        $body = I('post.body');
        $out_trade_no = I('post.out_trade_no');
        $total_fee = I('post.total_fee');
        $WeixinPay = new \WeixinPay($config["appid"], $openid, $config["mch_id"], $config["key"], $out_trade_no, $body, $total_fee);
        $content = $WeixinPay->pay();
        $this->ajaxReturn($content);
    }
    /**
     * 支付成功回调
     */
    
    public function callback(){
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];  
        //将服务器返回的XML数据转化为数组  
        $data = $this->xml2array($xml);  
        // 保存微信服务器返回的签名sign  
        $data_sign = $data['sign'];  
        // sign不参与签名算法  
        unset($data['sign']);  
        $sign = self::makeSign($data);  
          
        // 判断签名是否正确  判断支付状态  
        if ( $data['return_code']=='SUCCESS') {  
            $result = $data;  
            //获取服务器返回的数据  
            $order_sn = $data['out_trade_no'];          //订单单号  
            $openid = $data['openid'];                  //付款人openID  
            $total_fee = $data['total_fee'];            //付款金额  
            $transaction_id = $data['transaction_id'];  //微信支付流水号  
            //更新数据库  
            $this->updateDB($order_sn,$openid,$total_fee,$transaction_id);  
        }else{  
            $result = false;  
        }  
        // 返回状态给微信服务器  
        if ($result) {  
            $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';  
        }else{  
            $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';  
        }  
        echo $str;  
        return $result;  
    }
    
    //将xml格式转换成数组
    private function xml2array($xml) {
    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
    
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    
        $val = json_decode(json_encode($xmlstring), true);
    
        return $val;
    }
    //作用：生成签名
    private function getSign($Obj) {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }
    //作用：格式化参数，签名过程需要使用
    private function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = null;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}
