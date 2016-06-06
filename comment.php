<?php
date_default_timezone_set("Asia/Taipei");
include_once 'header.php';
if(isset($_POST["delete_msg"]))//deleting message part
{
    if($_POST["delete_msg"]=="delete")
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
            <script>alert('Message deleted !');</script>
            <?php
            header("Location: home.php");
        }
        else
        {
            echo "Error when deleting message.....";
        }
    }
}
if(isset($_POST["delete_cmt"]))//deleting comments/replies part
{
    if($_POST["delete_cmt"]=="delete")
    {
        $sql_select = "SELECT uid FROM comments WHERE cmt_id =".$_POST["del_msg_id"];
        $target = $conn->query($sql_select);
        $target_obj = $target->fetch(PDO::FETCH_OBJ);
        if($_SESSION['user']==$target_obj->uid)
        {
            $sql_del = "DELETE FROM comments WHERE cmt_id = ?";
            $stmt = $conn->prepare($sql_del);
            $stmt->bindValue(1,$_POST["del_msg_id"]);
            $stmt->execute();
            ?>
            <script>alert('Reply deleted !');</script>
            <?php
        }
        else
        {
            echo "Error when deleting message.....";
        }
    }
}
echo "<br>";

$parent_id = $_GET["m_id"];
if(isset($_POST['submit_comment']))
{
    if($_POST['comment']!='')
    {
        try
        {
           $uid = $_SESSION['user'];
           $msg = $_POST['comment'];
           $time = date('Y/m/d H:i:s');
           $insert = "INSERT INTO comments(cmt,uid,cmt_time,parent_id) VALUES(?,?,?,?)";
           $stmt = $conn->prepare($insert);
           $stmt->bindValue(1,$msg);
           $stmt->bindValue(2,$uid);
           $stmt->bindValue(3,$time);
           $stmt->bindValue(4,$parent_id);
           $stmt->execute();
           ?>
           <script>alert('successfully sent reply');</script>
           <?php
           echo "<meta http-equiv='refresh' content='0'>";//refresh the page
        }
        catch(Exception $e)
        {
           die(var_dump($e));
        }
    }
    else 
    {
        ?>
        <script>alert('Please check your input !');</script>
        <?php
    }
}

//display the parent message
$sql_select =   "SELECT msg_id,msg, username,msg_time,uid 
                FROM messages, users 
                WHERE messages.msg_id = $parent_id AND messages.uid = users.user_id";
$res=$conn->prepare($sql_select);
$res->execute();
?>
    <table class = "table-fill">
    <tr><th id = "text">Message</th>
    <th id = "name">By</th>
    <th id = "time">Time</th>
    <th id="actions">Delete</th>
    <?php
    while($row=$res->fetch(PDO::FETCH_OBJ))
    {
        echo "<tr><td>".$row -> msg."</td>";
        echo "<td>".$row -> username."</td>";
        echo "<td>".$row -> msg_time."</td>";
        if($_SESSION['user']==$row->uid)
        {
            ?>
            <td id="actions">
               <form action="comment.php" method="POST" >
                <button type="submit" id="del_btn" name="delete_msg" value ="delete" 
                style="border:0;background transparent;" 
                onclick="return confirm('Delete this message?')"/>
                <img style ="width:30px;height:30px;"src="delete.png" class = "invert" title = "Delete This Message" alt="submit" />
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
        echo "</tr>";
    }
    echo "</table><br>";

//display the replys to that message

$sql_select =   "SELECT cmt,cmt_id,username,cmt_time,comments.uid 
                FROM comments 
                INNER JOIN messages ON $parent_id=comments.parent_id AND parent_id = messages.msg_id
                INNER JOIN users ON users.user_id = comments.uid 
                ORDER BY cmt_time DESC ";
$res=$conn->prepare($sql_select);
$res->execute();
if($res->rowCount()!=0)
{
?>
    <table class = "table-fill">
    <tr><th id="text">Replies</th>
    <th id="name">By</th>
    <th id="time">Time</th>
    <th id="actions">Delete</th>
    <?php
    while($row=$res->fetch(PDO::FETCH_OBJ))
    {
        echo "<tr><td>".$row -> cmt."</td>";
        echo "<td>".$row -> username."</td>";
        echo "<td>".$row -> cmt_time."</td>";
        if($_SESSION['user']==$row->uid)
        {
            ?>
            <td id="actions">
               <form action="comment.php" method="POST" >
                <button type="submit" id="del_btn" name="delete_cmt" value ="delete" 
                style="border:0;background transparent;" 
                onclick="return confirm('Delete this reply?')"/>
                <img style ="width:30px;height:30px;"src="delete.png" class = "invert" title = "Delete This Message" alt="submit" />
                <?php
                echo "<input type='hidden' name='del_msg_id' value=".$row->cmt_id.">"
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
        echo "</tr>";
    }
    echo "</table>";
}
else
{
    echo "<div class='table-title'>";
    echo "<h3>No replies here</h3>";
    echo "</div>";
}
?>
<form action="comment.php" method="POST">
    <input type="hidden" name="submit_comment" value="1" />
    <?php echo "<input type='hidden' name='view_reply_parent' value=".$_SESSION['view_reply_parent'].">"; ?>
    <textarea name="comment" id = 'msg'></textarea><br />
    <button type="submit" id ='msg_submit'>Submit reply to this message</button>
</form>
<br>
<a href="home.php" id="back_to_home">Go Back</a>