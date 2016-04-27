<?php

/**
 * 审核订单相关
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
 * The control class of Review Indent
 *
 * @category ReviewIndent
 * @package  ReviewIndent_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ReviewIndentController extends Controller
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
     * 查看未审核订单
     * 
     * @return void
     */
    public function viewIndent()
    {
        $result = D('ReviewIndent')->viewIndent();
        echo json_encode($result);
    }

    /**
     * 开始审核
     * 
     * @return void
     */
    public function startReview()
    {
        $params = I('post.');
        $result = D('ReviewIndent')->startReview($params);
        echo json_encode($result);
    }
    
    /**
     * 查看属于我审核的订单
     * 
     * @return void
     */
    public function myReview()
    {
        $result = D('ReviewIndent')->myReview();
        echo json_encode($result);
    }
    
    /**
     * 发货
     * 
     * @return void
     */
    public function sendOutProduct()
    {
        $params = I('post.');
        $result = D('ReviewIndent')->sendOutProduct($params);
        echo json_encode($result);
    }
    
    /**
     * 查看所有未处理退款
     * 
     * @return void
     */
    public function viewDrawback()
    {
        $result = D('ReviewIndent')->viewDrawback();
        echo json_encode($result);
    }
    
    /**
     * 处理退款
     * 
     * @return void
     */
    public function dealDrawback()
    {
        $params = I('post.');
        $result = D('ReviewIndent')->dealDrawback($params);
        echo json_encode($result);
    }

}
