<?php
date_default_timezone_set("Asia/Taipei");
include_once 'header.php';
echo "<br>";
if(isset($_POST['view_reply_parent']))
{
$_SESSION['view_reply_parent'] = $_POST['view_reply_parent'];
}
$parent_id = $_SESSION['view_reply_parent'];
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
                WHERE messages.uid = users.user_id AND messages.msg_id = $parent_id";
$res=$conn->prepare($sql_select);
$res->execute();
?>
    <table class = "table-fill">
    <tr><th id = "text">Message</th>
    <th id = "name">By</th>
    <th id = "time">Time</th>
    <?php
    while($row=$res->fetch(PDO::FETCH_OBJ))
    {
        echo "<tr><td>".$row -> msg."</td>";
        echo "<td>".$row -> username."</td>";
        echo "<td>".$row -> msg_time."</td>";
        echo "</tr>";
    }
    echo "</table><br>";

//display the replys to that message

$sql_select =   "SELECT cmt,cmt_id,username,cmt_time 
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
    <?php
    while($row=$res->fetch(PDO::FETCH_OBJ))
    {
        echo "<tr><td>".$row -> cmt."</td>";
        echo "<td>".$row -> username."</td>";
        echo "<td>".$row -> cmt_time."</td>";
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