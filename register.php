<?php

session_start();
if(isset($_SESSION['user'])!="")
{
 header("Location: home.php");
}
include_once 'dbconnect.php';

if(isset($_POST['btn-signup']))
{
 $uname = $conn->quote($_POST['uname']);
 $email = $conn->quote($_POST['email']);
 $upass = $conn->quote(md5($_POST['pass']));
 $sql = "INSERT INTO users (username,email,password) VALUES($uname,$email,$upass)";
 $stmt = $conn->query($sql);
 if($stmt)
 {
        ?>
        <script>alert('successfully registered ');</script>
        <?php
        header("Location: index.php");
 }
 else
 {
  ?>
        <script>alert('error while registering you...');</script>
        <?php
 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>410221009 bboard</title>
<link rel="stylesheet" href="style.css" type="text/css" />

</head>
<body>
  <div id="header">
 <div id="left">
    <label>410221009 DBMS final project</label>
    </div>
  </div>
<center>
<div id="login-form">
<form method="post">
<table align="center" width="30%" border="0">
<tr>
<td><input type="text" name="uname" placeholder="User Name" required /></td>
</tr>
<tr>
<td><input type="email" name="email" placeholder="Your Email" required /></td>
</tr>
<tr>
<td><input type="password" name="pass" placeholder="Your Password" required /></td>
</tr>
<tr>
<td><button type="submit" name="btn-signup" id ="signup">Sign Me Up</button></td>
</tr>
<tr>
<td><a href="index.php">Sign In Here</a></td>
</tr>
</table>
</form>
</div>
</center>
</body>
</html>