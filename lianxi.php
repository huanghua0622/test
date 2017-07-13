<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/7/12
 * Time: 20:41
 */
function a($n,$m,$k)
{
   if(($m+$k-1) < $n){
           echo '$m+$k-1';
   }elseif($m+$k-1 == $n){
       echo '$n<br>';
   }else{
       echo '$m+$k-1-$n<br>';
   }
   $n--;
    if($n != 0){
       a($n,$k,$m);
    }
}
a(6,2,3);