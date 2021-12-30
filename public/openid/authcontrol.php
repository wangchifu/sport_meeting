<?php
session_start();

include "commonclass.php";
$obj = new TC_OID_BASE();
// $obj->setFinishFile("finish_auth.php");

//if(empty($_GET['openid_identifier'])) { $obj->displayError("請輸入公務帳號"); }
// $openid= "http://" . $_GET['openid_identifier'] .".openid.chc.edu.tw";
$openid= "http://openid.chc.edu.tw";
$obj->beginAuth($openid);


