<?php

/**
 * 管理商品相关
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
 * The control class of Manage Product
 *
 * @category ManageProduct
 * @package  ManageProduct_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ManageProductController extends Controller
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
     * 添加商品
     * 
     * @return void
     */
    public function createProduct()
    {
        $params = I('post.');
        $result = D('ManageProduct')->createProduct($params);
        echo json_encode($result);
    }

    /**
     * 搜索商品
     * 
     * @return void
     */
    public function searchProduct()
    {
        $params = I('post.');
        $result = D('ManageProduct')->searchProduct($params);
        echo json_encode($result);
    }

    /**
     * 将商品设置为使用状态
     * 
     * @return void
     */
    public function setUse()
    {
        $params = I('post.');
        $result = D('ManageProduct')->setUse($params);
        echo json_encode($result);
    }

    /**
     * 将商品设置为存档状态
     * 
     * @return void
     */
    public function setNoUse()
    {
        $params = I('post.');
        $result = D('ManageProduct')->setNoUse($params);
        echo json_encode($result);
    }

    /**
     * 删除商品
     * 
     * @return void
     */
    public function deleteProduct()
    {
        $params = I('post.');
        $result = D('ManageProduct')->deleteProduct($params);
        echo json_encode($result);
    }
    
    /**
     * 编辑商品，通过id获取已有的商品信息
     * 
     * @return void
     */
    public function editProduct()
    {
        $params = I('post.');
        $result = D('ManageProduct')->editProduct($params);
        echo json_encode($result);
    }
    
    /**
     * 保存编辑信息
     * 
     * @return void
     */
    public function saveEdit()
    {
        $params = I('post.');
        $result = D('ManageProduct')->saveEdit($params);
        echo json_encode($result);
    }
}
