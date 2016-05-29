<?php
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
         hi' <?php echo $_SESSION['username'];?>&nbsp;<a href="logout.php?logout">Sign Out</a>
        </div>
    </div>
</div>
<div class="table-title">
	<?php
        echo "<h3>Search Results of '".$_POST['message_target']."'</h3>";
    ?>
</div>

<?php
if(isset($_POST['search_submit']))
{
	$sequence = $_POST['message_target'];
 try
 {
    $sql_search = "SELECT msg_id,msg, username,msg_time,uid 
                FROM messages, users 
                WHERE messages.uid = users.user_id AND msg LIKE '%$sequence%'
                ORDER BY `messages`.`msg_time` DESC";
    $result = $conn->query($sql_search);
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
        if($_SESSION['user']==$row->uid)
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

 }
 catch(Exception $e)
 {
    die(var_dump($e));
 }
}
?>
<a href="home.php" id="back_to_home">Go Back</a>