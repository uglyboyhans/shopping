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

namespace Home\Model;

use Think\Model;

/**
 * The model class of Manage Product
 *
 * @category ManageProduct
 * @package  ManageProduct_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ManageProductModel extends Model
{

    //数据库表，查不到会报错
    protected $tableName = 'productdetail';
    //管理员id
    protected $adminid;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->adminid = session('adminLogin');
    }

    /**
     * 添加商品
     * 
     * @param array $args 商品字段
     * 
     * @return array
     */
    public function createProduct($args)
    {
        if (!$this->adminid) {//检查登录
            return [
                "result" => 1
            ];
        }
        $productname = $args['productname'] ? $args['productname'] : '';
        $price = $args['price'] ? $args['price'] : 99999999;
        $chargeunit = $args['chargeunit'] ? $args['chargeunit'] : '';
        $stock = $args['stock'] ? $args['stock'] : 0;
        $abstract = $args['abstract'] ? $args['abstract'] : '';
        $content = $args['content'] ? $args['content'] : '';
        $cover = $args['cover'] ? $args['cover'] : '';

        $sql = "insert into productdetail "
            . "(productname,price,chargeunit,stock,abstract,content,cover) values "
            . "('$productname','$price','$chargeunit','$stock','$abstract','$content','$cover')";
        if ($this->execute($sql)) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 1
            ];
        }
    }

}
