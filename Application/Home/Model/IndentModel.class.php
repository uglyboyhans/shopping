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
        return [
            "result" => 0,
        ];
    }

    /**
     * 从购物车批量
     * 
     * @param array $args 购物车id
     * 
     * @return array
     */
    public function payForCart($args)
    {
        return [
            "result" => 0
        ];
    }

}
