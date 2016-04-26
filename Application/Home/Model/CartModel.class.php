<?php

/**
 * 购物车相关
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
 * The model class of Cart
 *
 * @category Cart
 * @package  Cart_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class CartModel extends Model
{

    //数据库表，查不到会报错:
    protected $tableName = 'cart';
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
     * 添加商品到购物车
     * 
     * @param array $args 商品id，数量
     * 
     * @return array
     */
    public function addToCart($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        $productid = $args['productid'];
        if (!$productid) {
            return [
                "result" => 1,
                "error" => '参数错误'
            ];
        }
        $count = intval($args['count']) >= 1 ? intval($args['count']) : 1;
        $sql = "insert into cart (user,product,count)"
            . " values ($this->userid,$productid,$count)";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 1,
                "error" => '程序错误'
            ];
        }
    }

    /**
     * 查看购物车
     * 
     * @return array
     */
    public function viewCart()
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        //cart:c, productdetail:p
        $sql = "select c.cartid,c.count,c.product,p.productname,p.price,p.cover"
            . " from cart c left join productdetail p on c.product=p.productid"
            . " where c.user=$this->userid and c.isdelete=0"
            . " order by c.createtime desc";
        $result = $this->query($sql);
        if ($result) {
            return [
                "result" => 0,
                "count" => count($result),
                "cart" => $result
            ];
        } else {
            return [
                "result" => 1,
                "error" => '查找失败或购物车为空'
            ];
        }
    }

    /**
     * 从购物车删除（可批量）
     * 
     * @param array $args 购物车id
     * 
     * @return void
     */
    public function delete($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        $cartids = $args['cartid'] ? $args['cartid'] : 0;

        if (!is_array($cartids)) {//若不是数组
            $sql = "update cart set isdelete=1"
                . " where cartid=$cartids";
            if ($this->execute($sql) !== false) {
                return [
                    "result" => 0
                ];
            } else {
                return [
                    "result" => 1,
                    "error" => '删除失败'
                ];
            }
        } else {//如果是数组
            foreach ($cartids as $cartid) {
                if (!$cartid) {
                    continue;
                }
                $sql = "update cart set isdelete=1"
                    . " where cartid=$cartid";
                if ($this->execute($sql) === false) {
                    return [
                        "result" => 1,
                        "error" => '部分商品可能移除失败'
                    ];
                }
            }
            return [
                "result" => 0
            ];
        }
    }

    /**
     * 从购物车付款(可批量)
     * 
     * @param array $args 购物车id,地址
     * 
     * @return array
     */
    public function payFromCart($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }

        // 添加到订单（付款放在订单模块去做）:
        $cartids = $args['cartid'];
        if (is_array($cartids)) {//是数组，则批量
            //从购物车批量付款并添加到订单,直接传$args:
            if (0 !== D('Indent')->payForCart($args)['result']) {
                return [
                    "result" => 1,
                    "error" => "添加订单失败"
                ];
            }
        } else {
            //获取商品相关信息:
            $sql = "select product,count from cart where cartid=$cartids";
            $result = $this->query($sql);
            if ($result) {
                $param = [
                    'productid' => $result[0]['product'],
                    'count' => $result[0]['count'],
                    'address' => $args['address'],
                ];
            } else {
                return [
                    "result" => 3,
                    "error" => "商品已失效"
                ];
            }
            //购买单个商品并添加到订单:
            if (0 !== D('Indent')->payForOne($param)['result']) {
                return [
                    "result" => 1,
                    "error" => "添加订单失败"
                ];
            }
        }

        //从购物车删除
        return $this->delete(["cartid" => $cartids]);
    }

}
