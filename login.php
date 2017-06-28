<?php
    
    $data = $_POST;
    if ($data['username'] === 'itcast.cn' && $data['password'] === '123456' ){
        session_start();
        $_SESSION['is_login'] = 'yes';
        header("Location:index.php");  
        exit;
    } else {
        echo '<meta http-equiv="Refresh" content="4; url=login.html"><meta http-equiv="content-type" content="text/html; charset=UTF-8">';
        
        echo '<h3>账号或者密码错误</h3>';
    }

