<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/4
 * Time: 19:29
 */
mysqli_connect('192.168.38.99','sess','sess','session','3306');
mysqli_query('set names utf8');
class MySessionHandler extends SessionHandlerInterface{
    private $path;
    private $session;
    private $outtime;
    public function open($path,$session)
    {
        return true;
    }
    public function close()
    {
        return true;
    }
    public function read($id)
    {
        $sql = "selsect * from sess where sess_id = $id";
        $result = $this->db->execute($sql);
        if(!empty($result)){
            return $this->session = $result;
        }
    }
    public function write($id,$data)
    {
        $now = time();
        $newExp = $now+$this->outtime;
        $sql = "select * from sess where sess_id = '$id'";
        $result = $this->db->getOne($sql);
        if($data == '' || isset($data)){
            $data = $this->session;
        }
        if($result){
            $sql = "update sess set sess_time = '$outtime',sess_data = '$data' where sess_id = '$id'";
            $update_data = $this->db->execute($sql);
        }else{
            $sql = "insert into sess(sess_id,sess_time,sess_data) valus($id,$outtime,$data)";
            $update_data = $this->db->execute($sql);
        }
    }
    public function destroy($id)
    {
        $sql = "delete from sessions where sess_id = '$id'";
        $destory = $this->db->execute($sql);
        if($destory){
            return true;
        }else{
            return false;
        }
    }
    public function gc()
    {

    }
}