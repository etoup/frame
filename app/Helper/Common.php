<?php


namespace App\Helper;


use Hyperf\Utils\Arr;

class Common
{
    /**
     * @param null $data
     * @param string $message
     * @return array
     */
    public function success($data = null, string $message = '操作成功')
    {
        $items = [
            'code'   => 200,
            'status' => 'success',
            'message'=> $message
        ];
        isset($data) && $items['data'] = $data;
        return $items;
    }

    /**
     * @param string $message
     * @param int $code
     * @return array
     */
    public function fail(string $message = '操作失败', int $code = 200)
    {
        return [
            'code'   => $code,
            'status' => 'fail',
            'message'=> $message
        ];
    }

    /**
     * 生成随机数
     * @param int $length
     * @return int
     */
    public function generateNumber($length = 6)
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }

    /**
     * 生成随机字符串
     * @param int $length
     * @param string $chars
     * @return string
     */
    public function generateString($length = 6, $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz')
    {
        $chars = str_split($chars);

        $chars = array_map(function($i) use($chars) {
            return $chars[$i];
        }, array_rand($chars, $length));

        return implode($chars);
    }

    /**
     * xml to array 转换
     * @param $xml
     * @return mixed
     */
    public function xml2array($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 对银行卡号进行掩码处理
     * @param  string $bankCardNo 银行卡号
     * @return string             掩码后的银行卡号
     */
    function formatBankCardNo($bankCardNo){
        //截取银行卡号前4位
        $prefix = substr($bankCardNo,0,4);
        //截取银行卡号后4位
        $suffix = substr($bankCardNo,-4,4);
        return $prefix." **** **** **** ".$suffix;
    }

    /**
     * @param $bankCardNo
     * @param $bankName
     * @return string
     */
    function formatBankCardInfo($bankCardNo, $bankName){
        //截取银行卡号后4位
        $suffix = substr($bankCardNo,-4,4);
        return $bankName."（尾号：".$suffix."）";
    }

    /**
     * 对手机号进行掩码处理
     * @param  string $mobile 手机号
     * @param int $length     截取长度
     * @return string         掩码后的手机号
     */
    function formatMobile($mobile, $length = 4){
        //截取手机号码前3位
        $prefix = substr($mobile, 0, 3);
        //截取手机号码
        switch ($length) {
            case 5:
                $suffix = substr($mobile, -3, 3);
                $number = $prefix." ***** " . $suffix;
                break;
            default:
                $suffix = substr($mobile, -4, 4);
                $number = $prefix." **** " . $suffix;
        }
        return $number;
    }

    /**
     * 截取手机尾号
     * @param $mobile
     * @param int $start
     * @param int $len
     * @return bool|string
     */
    function mobileLastNumber($mobile, $start = -4, $len = 4 ) {
        return substr($mobile, $start, $len);
    }

    /**
     * 电话号码验证
     * @param $value
     * @return bool
     */
    public function mobileVerified($value) {
        /**
         * 中国电信：China Telecom
         * 133、149、153、173、177、180、181、189、199
         */
        $ct = "/^1((33|49|53|73|77|80|81|89|99)[0-9])\d{7}$/";
        /**
         * 中国联通：China Unicom
         * 130、131、132、145、155、156、166、171、175、176、185、186
         */
        $cu = "/^1(30|31|32|45|55|56|66|71|75|76|85|86)\d{8}$/";
        /**
         * 中国移动：China Mobile
         * 134(0-8)、135、136、137、138、139、147、150、151、152、157、158、159、178、182、183、184、187、188、198
         */
        $cm = "/^1(34[0-8]|(3[5-9]|47|5[012789]|78|8[23478]|98)[0-9])\d{7}$/";
        /**
         * 其他号段
         * 14号段以前为上网卡专属号段，如中国联通的是145，中国移动的是147等等。
         */
        $co = "/^14\d{9}$/";
        /**
         * 虚拟运营商
         * 电信：1700、1701、1702
         * 移动：1703、1705、1706
         * 联通：1704、1707、1708、1709、171
         */
        $cx = "/^1(700|701|702|703|705|706|66|704|707|708|709)\d{7}$/";
        /**
         * 卫星通信：1349
         */
        $cw = "/^1349\d{7}$/";
        if (preg_match($cm, $value)) {
            return true;
        } else if (preg_match($cu, $value)) {
            return true;
        } else if (preg_match($ct, $value)) {
            return true;
        } else if (preg_match($co, $value)) {
            return true;
        } else if (preg_match($cx, $value)) {
            return true;
        } else if (preg_match($cw, $value)) {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     * @param string $node
     * @return array
     */
    public function treeNode($data, string $node = 'children')
    {
        $items = array();
        foreach($data as $v){
            $items[$v['id']] = $v;
        }
        $tree = array();
        foreach($items as $k => $item){
            if(isset($items[$item['parent_id']])){
                $items[$item['parent_id']][$node][] = &$items[$k];
            }else{
                $tree[] = &$items[$k];
            }
        }
        return $tree;
    }

    /**
     * @param array $data
     * @param int $id
     * @return array
     */
    public function treeNodeRemove(array &$data, int &$id){
        foreach ($data as $k => $v) {
            if ($v['id'] === $id || $v['parent_id'] === $id) {
                unset($data[$k]);
                $this->treeNodeRemove($data, $v['id']);
            }
        }
        return $data;
    }

    /**
     * 整理排序所有分类
     * @param  array   $data       从数据库获取的分类
     * @return array
     */
    public function treePermissionNode($data)
    {
        $items = array();
        foreach($data as $v){
            $items[$v['id']] = $v;
        }
        $tree = array();
        foreach($items as $k => $item){
            if(isset($items[$item['parent_id']])){
                $items[$item['parent_id']]['children'][] = &$items[$k];
            }else{
                $tree[] = &$items[$k];
            }
        }
        return $tree;
    }

    /**
     * @param $url
     * @return bool
     */
    function check_url($url){
        if(!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)){
            return false;
        }
        return true;
    }
}