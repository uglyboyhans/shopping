<?php

/**
 * 反馈相关
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
 * The control class of Feedback
 *
 * @category Feedback
 * @package  Feedback_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class FeedbackController extends Controller
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
     * 用户添加反馈
     * 
     * @return void
     */
    public function addFeedback()
    {
        $params = I('post.');
        $result = D('Feedback')->addFeedback($params);
        echo json_encode($result);
    }
    
    /**
     * 管理员查看反馈
     * 
     * @return void
     */
    public function viewFeedback()
    {
        $params = I('post.');
        $result = D('Feedback')->viewFeedback($params);
        echo json_encode($result);
    }

}
