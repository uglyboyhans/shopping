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

namespace Home\Model;

use Think\Model;

/**
 * The model class of Product
 *
 * @category Product
 * @package  Product_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ProductModel extends Model
{

    //数据库表，查不到会报错
    protected $tableName = 'productdetail';
    //管理员id
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
     * 查找商品
     * 
     * @param array $args 搜索条件
     * 
     * @return array
     */
    public function searchProduct($args)
    {
        $productname = $args['productname'] ? $args['productname'] : '';
        $byprice = $args['byprice'] ? $args['byprice'] : 0;
        $byscore = $args['byscore'] ? $args['byscore'] : 0;
        $bysales = $args['bysales'] ? $args['bysales'] : 0;
        $pageindex = $args['pageindex'] ? $args['pageindex'] : 0;
        $pagesize = $args['pagesize'] ? $args['pagesize'] : 24;

        $sql = "select d.productid,d.productname,d.price,d.cover,s.sales,s.avescore"
            . " from productdetail d left join productsummary s on d.productid = s.product"
            . " where d.isused=1 and d.isdelete=0 and d.productname like '%$productname%'"
            . " order by s.summaryid desc";
        if ($byprice == 1) {
            $sql = $sql . ",d.price desc";
        }
        if ($byscore == 1) {
            $sql = $sql . ",s.avescore";
        }
        if ($bysales == 1) {
            $sql = $sql . ",s.sales";
        }
        $start = $pageindex * $pagesize;
        $sql = $sql . " limit $start,$pagesize";
        $result = $this->query($sql); //结果集
        if ($result) {
            return [
                'result' => 0,
                'count' => count($result),
                'product' => $result,
            ];
        } else {
            return[
                'result' => 1
            ];
        }
    }

    public function productDetail($args)
    {
        $productid = $args['productid'];
        $sql = "select d.productname,d.price,d.cover,s.sales,s.avescore,"
            . " d.chargeunit,d.stock,d.abstract,d.content,d.createtime"
            . " from productdetail d"
            . " left join productsummary s on d.productid = s.product"
            . " where d.productid = $productid and d.isdelete=0 and d.isused=1";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
            ];
        } else {
            return [
                "result" => 0,
                "product" => $result[0],
            ];
        }
    }

}
