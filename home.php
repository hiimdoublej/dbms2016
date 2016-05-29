<?php
session_start();
include_once 'dbconnect.php';
date_default_timezone_set("Asia/Taipei");

if(!isset($_SESSION['user']))
{
 header("Location: index.php");
}

$sql_select = "SELECT * FROM users WHERE user_id=".$_SESSION['user'];
$res=$conn->query($sql_select);
$userRow=$res->fetch(PDO::FETCH_BOTH);

if(isset($_POST["delete_action"]))
{
if($_POST["delete_action"]=="delete")
{
    $sql_select = "SELECT uid FROM messages WHERE msg_id =".$_POST["del_msg_id"];
    $target = $conn->query($sql_select);
    $target_obj = $target->fetch(PDO::FETCH_OBJ);
    if($_SESSION['user']==$target_obj->uid)
    {
        $sql_del = "DELETE FROM messages WHERE messages.msg_id = ?";
        $stmt = $conn->prepare($sql_del);
        $stmt->bindValue(1,$_POST["del_msg_id"]);
        $stmt->execute();
        ?>
        <script>alert('message deleted');</script>
        <?php
    }
    else
    {
        echo "Error when deleting message.....";
    }
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
<div class="table-title">
        <h3>Bulletin Board</h3>
</div>

<form action="search.php" method="POST">
    <input type="hidden" name="search_submit" value="1" />
    <textarea name="message_target" id = 'msg_search_box'></textarea>
        <button type="submit" id ='msg_search'>Search for message</button>
    <br />
</form>


<?php
$sql_select =   "SELECT msg_id,msg, username,msg_time,uid 
                FROM messages, users 
                WHERE messages.uid = users.user_id
                ORDER BY `messages`.`msg_time` DESC";
$result = $conn->query($sql_select);
if(count($result) > 0)
{
	?>
    <table class = "table-fill">
    <tr><th>Message</th>
    <th>By</th>
    <th>Time</th>
    <th id="actions">Delete</th>
    <?php
    while($row=$result->fetch(PDO::FETCH_OBJ))
    {
    	echo "<tr><td>".$row -> msg."</td>";
        echo "<td>".$row -> username."</td>";
    	echo "<td>".$row -> msg_time."</td>";
        if($userRow['user_id']==$row->uid)
        {
            ?>
            <td id="actions">
               <form action="home.php" method="POST" >
                <button type="submit" id="del_btn" name="delete_action" value ="delete" 
                style="border:0;background transparent;" 
                onclick="return confirm('Delete this message?')"/>
                <img style ="width:30px;height:30px;"src="icons/delete.png" class = "invert" title = "Delete This Message" alt="submit" />
                <?php
                echo "<input type='hidden' name='del_msg_id' value=".$row->msg_id.">"
                ?>
                </form>
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
    <button type="submit" id ='msg_submit'>Submit message</button>
</form>

<?php
if(isset($_POST['submit']))
{
 try
 {
     $uid = $userRow['user_id'];
     $msg = $_POST['message'];
     $time = date('Y/m/d H:i:s');
     $insert = "INSERT INTO messages(msg,uid,msg_time) VALUES(?,?,?)";
     $stmt = $conn->prepare($insert);
     $stmt->bindValue(1,$msg);
     $stmt->bindValue(2,$uid);
     $stmt->bindValue(3,$time);
     $stmt->execute();
      ?>
        <script>alert('successfully sent message');</script>
        <?php
         echo "<meta http-equiv='refresh' content='0'>";
 }
 catch(Exception $e)
 {
    die(var_dump($e));
 }
}
?>
</body>
</html>