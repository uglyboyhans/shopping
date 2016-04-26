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
    protected $adminId;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->adminId = session('adminLogin');
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
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productname = $args['productname'] ? $args['productname'] : '';
        $price = $args['price'] ? $args['price'] : 99999999;
        $chargeunit = $args['chargeunit'] ? $args['chargeunit'] : '';
        $stock = $args['stock'] ? $args['stock'] : 0;
        $abstract = $args['abstract'] ? $args['abstract'] : '';
        $content = $args['content'] ? $args['content'] : '';
        $cover = $args['cover'] ? $args['cover'] : '';

        $sql = "insert into productdetail"
            . " (productname,price,chargeunit,stock,abstract,content,cover) values"
            . " ('$productname',$price,'$chargeunit','$stock','$abstract','$content','$cover')";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 1,
                "error" => "添加商品失败"
            ];
        }
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
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productName = $args['productname'] ? $args['productname'] : '';
        $byPrice = $args['byprice'] ? $args['byprice'] : 0;
        $byScore = $args['byscore'] ? $args['byscore'] : 0;
        $bySales = $args['bysales'] ? $args['bysales'] : 0;
        $pageIndex = $args['pageindex'] ? $args['pageindex'] : 1;
        $pageSize = $args['pagesize'] ? $args['pagesize'] : 24;

        $sql = "select d.productid,d.productname,d.price,d.cover,d.isused,s.sales,s.avescore"
            . " from productdetail d left join productsummary s on d.productid = s.product"
            . " where d.isdelete=0 and d.productname like '%$productName%'"
            . " order by s.summaryid desc";
        if ($byPrice == 1) {
            $sql = $sql . ",d.price desc";
        }
        if ($byScore == 1) {
            $sql = $sql . ",s.avescore";
        }
        if ($bySales == 1) {
            $sql = $sql . ",s.sales";
        }
        if ($pageIndex < 1) {
            $pageIndex = 1;
        }
        if ($pageSize < 1) {
            $pageSize = 1;
        }
        $start = ($pageIndex - 1) * $pageSize;
        $sql = $sql . " limit $start,$pageSize";
        $result = $this->query($sql); //结果集
        if ($result) {
            return [
                'result' => 0,
                'count' => count($result),
                'product' => $result,
            ];
        } else {
            return[
                'result' => 1,
                "error" => "查询失败，或商品为空"
            ];
        }
    }

    /**
     * 将商品设置为使用状态
     * 
     * @param array $args id
     * 
     * @return array
     */
    public function setUse($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productId = $args['productid'];
        //将使用状态isused置为1
        $sql = "update productdetail set isused=1 where productid = $productId";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 1,
                "error" => "修改失败"
            ];
        }
        //检查总表是否有这条
        if (!$this->_isExistInSummaryByProductId($productId)) {
            //将商品添加到商品总表
            $sql = "insert into productsummary"
                . " (product,sales,avescore,createtime,updatetime) values"
                . " ($productId,0,5,now(),now())";
            if ($this->execute($sql) === false) {
                return [
                    "result" => 1,
                    "error" => "更新总表失败"
                ];
            } else {
                return [
                    "result" => 0
                ];
            }
        } else {
            //若已经有这条，将其恢复
            $sql = "update productsummary set isdelete=0 where product=$productId";
            if ($this->execute($sql) === false) {
                return [
                    "result" => 1,
                    "error" => "恢复到总表失败"
                ];
            } else {
                return [
                    "result" => 0
                ];
            }
        }
    }

    /**
     * 将商品设置为存档状态
     * 
     * @param array $args id
     * 
     * @return array
     */
    public function setNoUse($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productId = $args['productid'];
        //将使用状态isused置为0
        $sql = "update productdetail set isused=0 where productid = $productId";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "存档失败"
            ];
        }

        //将其从总表删除
        $sql = "update productsummary set isdelete=1 where product=$productId";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "从总表删除失败"
            ];
        } else {
            return [
                "result" => 0
            ];
        }
    }

    /**
     * 删除商品
     * 
     * @param array $args id
     * 
     * @return array
     */
    public function deleteProduct($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productId = $args['productid'];
        //删除商品：将isdelete置为1
        $sql = "update productdetail set isdelete=1 where productid=$productId";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "删除失败"
            ];
        } else {
            return [
                "resutl" => 0
            ];
        }
    }

    /**
     * 编辑商品，通过id获取已有的商品信息
     * 
     * @param array $args 商品id
     * 
     * @return array
     */
    public function editProduct($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productId = $args['productid'];
        $sql = "select productname,price,cover,chargeunit,stock,abstract,content"
            . " from productdetail"
            . " where productid = $productId";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "获取商品信息失败"
            ];
        } else {
            return [
                "result" => 0,
                "product" => $result[0],
            ];
        }
    }

    /**
     * 保存编辑信息
     * 
     * @param array $args 商品信息
     * 
     * @return array
     */
    public function saveEdit($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $productId = $args['productid'];
        $productname = $args['productname'] ? $args['productname'] : '';
        $price = $args['price'] ? $args['price'] : 99999999;
        $chargeunit = $args['chargeunit'] ? $args['chargeunit'] : '';
        $stock = $args['stock'] ? $args['stock'] : 0;
        $abstract = $args['abstract'] ? $args['abstract'] : '';
        $content = $args['content'] ? $args['content'] : '';
        $cover = $args['cover'] ? $args['cover'] : '';

        $sql = "update productdetail set"
            . " productname = '$productname',"
            . " price = $price,"
            . " chargeunit = '$chargeunit',"
            . " stock = $stock,"
            . " abstract = '$abstract',"
            . " content = '$content',"
            . " cover = '$cover'"
            . " where productid = $productId";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 1,
                "error" => "保存失败"
            ];
        }
    }

    /**
     * 通过productId判断是否在总表中存在
     * 
     * @param int $productId 商品id 
     * 
     * @access private
     * 
     * @return bool
     */
    private function _isExistInSummaryByProductId($productId)
    {
        $sql = "select summaryid from productsummary where product =$productId";
        if (!$this->query($sql)) {
            return false;
        } else {
            return true;
        }
    }

}
