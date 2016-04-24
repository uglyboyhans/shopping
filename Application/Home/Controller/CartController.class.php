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

namespace Home\Controller;

use Think\Controller;

/**
 * The control class of Cart
 *
 * @category Cart
 * @package  Cart_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class CartController extends Controller
{

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加商品到购物车
     * 
     * @return void
     */
    public function addToCart()
    {
        $params = I('post.');
        $result = D('Cart')->addToCart($params);
        echo json_encode($result);
    }

    /**
     * 查看购物车
     * 
     * @return void
     */
    public function viewCart()
    {
        $result = D('Cart')->viewCart();
        echo json_encode($result);
    }

    /**
     * 从购物车删除（可批量）
     * 
     * @return void
     */
    public function delete()
    {
        $params = I('post.');
        $result = D('Cart')->delete($params);
        echo json_encode($result);
    }

    /**
     * 从购物车付款(可批量)
     * 
     * @return void
     */
    public function payFromCart()
    {
        $params = I('post.');
        $result = D('Cart')->PayFromCart($params);
        echo json_encode($result);
    }

}
