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

namespace Home\Controller;

use Think\Controller;

/**
 * The control class of Indent
 *
 * @category Indent
 * @package  Indent_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class IndentController extends Controller
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
     * 单个付款
     * 
     * @return void
     */
    public function payForOne()
    {
        $params = I('post.');
        $result = D('Indent')->payForOne($params);
        echo json_encode($result);
    }

    /**
     * 查看订单
     * 
     * @return void
     */
    public function viewIndent()
    {
        $result = D('Indent')->viewIndent();
        echo json_encode($result);
    }

    /**
     * 从订单删除
     * 
     * @return void
     */
    public function delete()
    {
        $params = I('post.');
        $result = D('Indent')->delete($params);
        echo json_encode($result);
    }

    /**
     * 申请退款
     * 
     * @return void
     */
    public function applyDrawback()
    {
        $params = I('post.');
        $result = D('Indent')->applyDrawback($params);
        echo json_encode($result);
    }

    /**
     * 收货
     * 
     * @return void
     */
    public function receipt()
    {
        $params = I('post.');
        $result = D('Indent')->receipt($params);
        echo json_encode($result);
    }

    /**
     * 添加评价
     * 
     * @return void
     */
    public function comment()
    {
        $params = I('post.');
        $result = D('Indent')->comment($params);
        echo json_encode($result);
    }

}
