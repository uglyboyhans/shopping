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

namespace Home\Model;

use Think\Model;

/**
 * The model class of Feedback
 *
 * @category Feedback
 * @package  Feedback_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class FeedbackModel extends Model
{

    //数据库表，查不到会报错:
    protected $tableName = 'feedback';
    //管理员id:
    protected $adminId;
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
        $this->adminId = session('adminLogin');
    }

    /**
     * 用户添加反馈
     * 
     * @param array $args 反馈内容
     * 
     * @return array
     */
    public function addFeedback($args)
    {
        if (!$this->userid) {
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        $content = $args['content'];
        if (!$content) {
            return [
                "result" => 1,
                "error" => "参数错误"
            ];
        }
        $sql = "insert into feedback"
            . " (user,content) values"
            . " ($this->userid,'$content')";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 0,
            ];
        } else {
            return [
                "result" => 1,
                "error" => "添加反馈失败"
            ];
        }
    }

    /**
     * 管理员查看反馈
     * 
     * @param array $args 搜索条件
     * 
     * @return array
     */
    public function viewFeedback($args)
    {
        if (!$this->adminId) {
            return [
                "result" => 2,
                "error" => "管理员未登录"
            ];
        }
        $content = $args['content'] ? $args['content'] : "";
        $bytime = $args['bytime'] == 1 ? 1 : 0;
        $pageIndex = $args['pageindex'] > 0 ? $args['pageindex'] : 1;
        $pageSize = $args['pagesize'] > 0 ? $args['pagesize'] : 24;
        $sql = "select feedbackid,content,createtime from feedback"
            . " where content like '%$content%' order by createtime";
        if ($bytime !== 1) {
            $sql = $sql . " desc";
        }
        $start = ($pageIndex - 1) * $pageSize;
        $sql = $sql . " limit $start,$pageSize";
        $result = $this->query($sql); //结果集
        if ($result) {
            return [
                'result' => 0,
                'count' => count($result),
                'feedback' => $result,
                'pageindex' => $pageIndex,
                'pagesize'=>$pageSize,
            ];
        } else {
            return[
                'result' => 1,
                "error" => "查询失败或没有反馈"
            ];
        }
    }

}
