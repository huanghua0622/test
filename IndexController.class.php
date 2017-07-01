<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/1
 * Time: 19:43
 */
namespace MySQL\Controller;
use Think\Controller;
class IndexController extends Controller{
    public function add()
    {
        $sql = "insert into binlog values (500)";
        $result = M()->execute($sql);
        var_dump($result);
    }
    public function get()
    {
        $sql = "select * from binlog";
        $result = M()->query($sql);
        echo '<pre>';
        var_dump($result);
    }
}