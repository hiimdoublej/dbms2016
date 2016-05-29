<?php
session_start();
include_once 'dbconnect.php';

if(isset($_SESSION['user'])!="")
{
 header("Location: home.php");
}
if(isset($_POST['btn-login']))
{
 $email = $conn->quote($_POST['email']);
 $upass = md5($_POST['pass']);
 $sql = "SELECT * FROM users WHERE email=$email";
 $res=$conn->query($sql);
 $row=$res->fetch(PDO::FETCH_BOTH);
 if($row['password']==$upass)
 {
  	$_SESSION['user'] = $row['user_id'];
    $_SESSION['username'] = $row['username'];
  	?>
  	<script>alert('Login successful');</script>
  	<?php
  	header("Location:home.php");
 }
 else
 {
  ?>
        <script>alert('wrong details');</script>
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
<table id = "login" align="center" width="30%" border="0">
<tr>
<td><input type="text" name="email" placeholder="Your Email" required /></td>
</tr>
<tr>
<td><input type="password" name="pass" placeholder="Your Password" required /></td>
</tr>
<tr>
<td><button type="submit" name="btn-login" id ="login">Sign In</button></td>
</tr>
<tr>
<td><a href="register.php">Sign Up Here</a></td>
</tr>
</table>
</form>
</div>
</center>
</body>
</html>