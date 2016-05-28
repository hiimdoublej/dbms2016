<?php
session_start();
include_once 'dbconnect.php';
date_default_timezone_set("Asia/Taipei");

if(!isset($_SESSION['user']))
{
 header("Location: index.php");
}


$res=mysql_query("SELECT * FROM users WHERE user_id=".$_SESSION['user']);
$userRow=mysql_fetch_array($res);

if($_REQUEST["action"]=="deleteMessage")
{
    $target = mysql_query("SELECT uid FROM messages WHERE msg_id =".$_REQUEST["msgid"]);
    $target_obj = mysql_fetch_object($target);
    if($_SESSION['user']==$target_obj->uid)
    {
        echo "deletion is valid";
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['email']; ?></title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
  <div id="header">
 <div id="left">
    <label>410221009 DBMS final project</label>
    </div>
    <div id="right">
     <div id="content">
         hi' <?php echo $userRow['username'];?>&nbsp;<a href="logout.php?logout">Sign Out</a>
        </div>
    </div>
</div>

<?php
$sql_select =   "SELECT msg_id,msg, username,msg_time,uid 
                FROM messages, users 
                WHERE messages.uid = users.user_id
                ORDER BY `messages`.`msg_time` DESC";
$result = mysql_query($sql_select);
if(count($result) > 0)
{
	?>
	<div class="table-title">
		<h3>Bulletin Board</h3>
	</div>
    <table class = "table-fill">
    <tr><th>Message</th>
    <th>By</th>
    <th>Time</th>
    <th>Delete ?</th>
    <?php
    while($row=mysql_fetch_object($result))
    {
    	echo "<tr><td>".$row -> msg."</td>";
        echo "<td>".$row -> username."</td>";
    	echo "<td>".$row -> msg_time."</td>";
        if($userRow['user_id']==$row->uid)
        {
            ?>
            <td>
                <?php
                echo "<a href = home.php?action=deleteMessage&msgid=".$row->msg_id.">";
                ?>
                <img src="icons/delete.png" style="width:30px;height:30px;" class = "invert" title = "Delete This Message" >
                </a>
            </td>
        </tr>
            <?php
        }
        else 
        {
            echo "<td></td></tr>";
        }
    }
    echo "</table>";
}
else
{
	echo "No message here ! be the first !";
}
?>

<form action="home.php" method="POST">
    <input type="hidden" name="submit" value="1" />
    <textarea name="message" id = 'msg'></textarea><br />
    <input type="submit" id ='msg_submit' value="Submit message!" />
</form>

<?php
if(isset($_POST['submit']))
{
 $uid = $userRow['user_id'];
 $msg = mysql_real_escape_string($_POST[message]);
 $time = date('Y/m/d H:i:s');
 
 if(mysql_query("INSERT INTO messages(msg,uid,msg_time) VALUES('$msg','$uid','$time')"))
 {
  ?>
        <script>alert('successfully sent message');</script>
        <?php
         echo "<meta http-equiv='refresh' content='0'>";
 }
 else
 {
  ?>
        <script>alert('error while sending message');</script>
        <?php
 }
}
?>
</body>
</html>