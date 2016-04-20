<?php

/**
 * 商品相关
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
 * The control class of  Product
 *
 * @category Product
 * @package  Product_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ProductController extends Controller
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
     * 查找商品
     * 
     * @return void
     */
    public function searchProduct()
    {
        $params = I('post.');
        $result = D('Product')->searchProduct($params);
        echo json_encode($result);
    }

    /**
     * 查看单个商品详情
     * 
     * @return void
     */
    public function productDetail()
    {
        $params = I('get.');
        if (!$params['productid']) {
            $params = I('post.');
            $result = D('Product')->productDetail($params);
        } else {
            $result = D('Product')->productDetail($params);
        }
        echo json_encode($result);
    }

}
