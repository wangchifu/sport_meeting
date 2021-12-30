<?php

require_once "commonclass.php";
$obj = new TC_OID_BASE();
session_start();

if( $obj->getResponseStatus($msg) >0) {
  $arr= $obj->getResponseArray();
  //header("Content-Type:text/html; charset=utf-8");
  //print "<pre>";
    $identity=$arr['identity'];
    $openid1 = explode(".openid",$identity);
    $openid2 = explode("//",$openid1[0]);
    $openid = $openid2[1];
    $guid=$arr['guid'];
    $name= $arr['fullname'];
    $tmp=json_decode($arr['titleStr']);
    $unit=$tmp[0]->sid;//單位
    $title=$tmp[0]->title[0];//職稱

    if ($title=='學生')exit;

  header("Location: http://".$_SERVER['HTTP_HOST']."/openid_get?openid=".$openid."&guid=".$guid."&name=".$name."&unit=".$unit."&title=".$title);
  //print_r($arr);
  /*
Array
(
    [identity] => http://example.openid.tc.edu.tw/
    [fullname] => 張OO
    [email] => example@tc.edu.tw
    [schooldistrict] => 西屯區
    [schoolname] => OO國中
    [schooltitle] => 專任教師
    [schooltype] => 市立國民中學
)
  */
}else print $msg;


