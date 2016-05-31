<?php
date_default_timezone_set("Asia/Taipei");
session_start();
include_once 'dbconnect.php';
if(!isset($_SESSION['user']))
{
 header("Location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>410221009 bboard</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
  <div id="header">
 <div id="left">
    <label>410221009 DBMS final project</label>
    </div>
    <div id="right">
     <div id="content">
     	    <a href="https://github.com/hiimdoublej/dbms2016">Github</a>
         hi' <?php echo $_SESSION['username'];?>&nbsp;<a href="logout.php?logout">Sign Out</a>
        </div>
    </div>
</div>