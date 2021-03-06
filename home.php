<?php
date_default_timezone_set("Asia/Taipei");
include_once 'header.php';
if(!isset($_SESSION['user']))
{
 header("Location: index.php");
}
$sql_select = "SELECT * FROM users WHERE user_id=".$_SESSION['user'];
$res=$conn->query($sql_select);
$userRow=$res->fetch(PDO::FETCH_BOTH);

if(isset($_POST["delete_action"]))//deleting message part
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
        }
        else
        {
            echo "Error when deleting message.....";
        }
    }
}

?>
<div class="table-title">
        <h3>Bulletin Board</h3>
</div>

<form action="search.php" method="POST">
    <input type="hidden" name="search_submit" value="1" />
    <input name="message_target" id = 'msg_search_box'></textarea>
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
    <tr><th id="text">Message</th>
    <th id="name">By</th>
    <th id="time">Time</th>
    <th id="actions">Delete</th>
    <th id="replies">Replies</th>
    <?php
    while($row=$result->fetch(PDO::FETCH_OBJ))
    {
    	echo "<tr><td>".nl2br($row -> msg)."</td>";
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
                <img style ="width:100%;height:100%;"src="delete.png" class = "invert" title = "Delete This Message" alt="submit" />
                <?php
                echo "<input type='hidden' name='del_msg_id' value=".$row->msg_id.">"
                ?>
                </form>
                </a>
            </td>
            <?php
        }
        else 
        {
            echo "<td></td>";
        }
        $sql_count =   "SELECT COUNT(cmt)
                FROM comments 
                INNER JOIN messages ON comments.parent_id = $row->msg_id AND msg_id = comments.parent_id
                INNER JOIN users ON users.user_id=messages.uid
                ORDER BY cmt_time DESC ";
                $num_of_replies = $conn->query($sql_count);
                $num_result = $num_of_replies->fetch(PDO::FETCH_NUM)[0];
                if($num_result<2)
                {
                    echo "<td id='actions'>";
                    echo "<form action='comment.php' method='GET'>";
                    echo "<button type='submit' id='view_replies' name='m_id' 
                    value='".$row->msg_id."'>$num_result reply</button>";
                    echo "</form></td></tr>";
                }
                else
                {
                    echo "<td id='actions'>";
                    echo "<form action='comment.php' method='GET'>";
                    echo "<button type='submit' id='view_replies' name='m_id' 
                    value='".$row->msg_id."'>$num_result replies</button>";
                    echo "</form></td></tr>";
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
    if($_POST['message']!='')
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
        echo "<meta http-equiv='refresh' content='0'>";//refresh the page
     }
     catch(Exception $e)
     {
        die(var_dump($e));
     }
    else
    {
        ?>
        <script>alert('Please check your input !');</script>
        <?php
    }
}
?>
</body>
</html>