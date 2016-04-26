<?php

/**
 * 订单相关
 *
 * PHP version 5
 *
 * @category Appserver
 * @package  Shopping
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 *
 */

namespace Home\Model;

use Think\Model;

/**
 * The model class of Indent
 *
 * @category Indent
 * @package  Indent_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class IndentModel extends Model
{

    //数据库表，查不到会报错:
    protected $tableName = 'indent';
    //用户id：
    protected $userid;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userid = session('login');
    }

    /**
     * 单个付款
     * 
     * @param array $args 商品id、数量
     * 
     * @return void
     */
    public function payForOne($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        $productid = $args['productid'];
        if (!$productid) {
            return [
                "result" => 1,
            ];
        }
        $address = $args['address'];
        if (!$address) {
            return [
                "result" => 1,
            ];
        }
        $count = intval($args['count']) >= 1 ? intval($args['count']) : 1;
        //查出价格
        $sql = "select price from productdetail where productid=$productid";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
            ];
        } else {
            //算出总价
            $total = floatval($count * $result[0]['price']);
        }

        /**
         * @todo 付款
         */
        //添加到订单：
        $sql = "insert into indent (user,product,count,total,address,updatetime)"
            . " values ($this->userid,$productid,$count,$total,'$address',now())";
        if ($this->execute($sql) !== false) {
            /**
             * @todo 添加通知
             */
            return [
                "result" => 0,
            ];
        } else {
            return [
                "result" => 1,
            ];
        }
    }

    /**
     * 从购物车批量付款
     * 
     * @param array $args 购物车id:[2,4,5]，地址
     * 
     * @return array
     */
    public function payForCart($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        $total = floatval(0); //总价
        $params = []; //添加到Indent的参数
        $cartids = '('; //in查询条件
        $cartid = $args['cartid']; //是个数组
        $address = $args['address'];
        if (!$address || !$cartid) {
            return [
                "result" => 1,
                "error" => "参数错误",
            ];
        }
        foreach ($cartid as $key => $value) {
            if (!$value) {
                array_splice($cartid, $key, 1); //剔除空元素
            }
        }
        $cartids = $cartids . implode(',', $cartid) . ')'; //拼接字符串
        //从cart里查出相关信息：
        //cart:c, productdetail:p
        $sql = "select c.count as count,c.product as productid,p.price as price"
            . " from cart c left join productdetail p on c.product=p.productid"
            . " where c.cartid in $cartids";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1
            ];
        } else {
            foreach ($result as $value) {
                //$value = ["count"=>n,"productid"=>x,"price"=>y]
                $value['total'] = floatval($value['count'] * $value['price']);
                //更新总价:
                $total = $total + floatval($value['count'] * $value['price']);
                //push到添加Indent的参数:
                array_push($params, $value);
            }
        }
        /**
         * @todo 付款
         */
        //添加到indent表
        foreach ($params as $value) {
            $count = $value['count'];
            $productid = $value['productid'];
            $oneTotal = floatval($value['total']);
            $sql = "insert into indent (user,product,count,total,address,updatetime)"
                . " values ($this->userid,$productid,$count,$oneTotal,'$address',now())";
            if ($this->execute($sql) !== false) {
                /**
                 * @todo 添加通知
                 */
            } else {
                return [
                    "result" => 1,
                ];
            }
        }

        return [
            "result" => 0
        ];
    }

    /**
     * 查看订单
     * 
     * @return array
     */
    public function viewIndent()
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        //indent:i productdetail:p
        $sql = "select i.indentid,p.productname,p.price,i.count,i.total,"
            . " i.status,p.cover,i.createtime,i.updatetime"
            . " from indent i left join productdetail p"
            . " on i.product=p.productid"
            . " where i.user = $this->userid and i.isdelete=0"
            . " order by updatetime desc";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
            ];
        } else {
            return [
                "rusult" => 0,
                "count" => count($result),
                "indent" => $result,
            ];
        }
    }

    /**
     * 从订单删除
     * 
     * @param array $args 订单id
     * 
     * @return void
     */
    public function delete($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        $indentid = $args['indentid'];
        $sql = "select status from indent where indentid=$indentid";
        $status = $this->query($sql)[0]['status'];
        if ($status == 2 || $status == 3 || $status == 5) {
            $sql = "update indent set isdelete=1 where indentid=$indentid";
            if ($this->execute($sql) !== false) {
                return [
                    "result" => 0
                ];
            }
        } else {//交易未完成
            return [
                "result" => 3,
            ];
        }
    }

    /**
     * 申请退款
     * 
     * @param array $args 订单id,申请原因
     * 
     * @return array
     */
    public function applyDrawback($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        $indentid = $args['indentid'];
        $reason = $args['reason'] ? $args['reason'] : "";
        //insert到退款清单：
        $sql = "insert into drawbacklist (indent,reason) values"
            . " ($indentid,'$reason')";
        if ($this->execute($sql) !== false) {
            //update订单状态
            $sql = "update indent set status=4,updatetime=now()"
                . " where indentid = $indentid";
            if ($this->execute($sql) !== false) {
                return [
                    "result" => 0
                ];
            } else {
                return ["result" => 1];
            }
        } else {
            return ["result" => 1];
        }
    }

    /**
     * 收货
     * 
     * @param array $args 订单id
     * 
     * @return array
     */
    public function receipt($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        $indentid = $args['indentid'];
        //update订单表状态
        $sql = "update indent set status=2,updatetime=now()"
            . " where indentid=$indentid";
        if ($this->execute($sql) !== false) {
            //update物流表状态
            $sql = "update logisticslist set recievetime=now()"
                . " where indent=$indentid";
            if ($this->execute($sql) !== false) {
                return [
                    "result" => 0
                ];
            } else {
                return ["result" => 1];
            }
        } else {
            return ["result" => 1];
        }
    }

    /**
     * 添加评价
     * 
     * @param array $args 评论内容、分数
     * 
     * @return array
     */
    public function comment($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
            ];
        }
        $indentid = $args['indentid'];
        $score = floatval($args['score']) > 0 ? floatval($args['score']) : 5;
        if ($score > 5) {
            $score = 5;
        }
        $content = $args['content'] ? $args['content'] : '系统默认好评';

        //先找出商品id
        $sql = "select product from indent where indentid=$indentid";
        $result = $this->query($sql);
        if ($result) {
            $product = $result[0]['product'];
            //添加到评价表
            $sql = "insert into productcomment"
                . " (product,user,content,score) values"
                . " ($product,$this->userid,'$content',$score)";
            if ($this->execute($sql) !== false) {
                //更新indent状态
                $sql = "update indent set status=3,updatetime=now()"
                    . " where indentid=$indentid";
                if ($this->execute($sql) !== false) {
                    return [
                        "result" => 0
                    ];
                } else {
                    return ["result" => 1];
                }
            } else {
                return ["result" => 1];
            }
        } else {
            return ["result" => 1];
        }
    }

}
