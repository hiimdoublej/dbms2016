<?php
include_once 'header.php';
if(isset($_POST['search_submit'])&$_POST['message_target']!='')
{
    echo "<div class='table-title'>";
    echo "<h3>Search Results of '".$_POST['message_target']."'</h3>";
    echo "</div>";
    try
    {
        $sequence = '%'.$_POST['message_target'].'%';
        $search = "SELECT msg_id,msg, username,msg_time,uid 
                    FROM messages, users 
                    WHERE messages.uid = users.user_id AND msg LIKE ?
                    ORDER BY `messages`.`msg_time` DESC";
        $stmt = $conn->prepare($search);
        $stmt->bindValue(1,$sequence,PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount()!=0)
        {
            echo "<div class='table-title'>";
            echo "<h3>".$stmt->rowCount()." results:</h3>";
            echo "</div>";
        	?>
            <table class = "table-fill">
            <tr><th id="text">Message</th>
            <th id="name">By</th>
            <th id="time">Time</th>
            <th id="actions">Delete</th>
            <?php
            while($row=$stmt->fetch(PDO::FETCH_OBJ))
            {
            	echo "<tr><td>".nl2br($row -> msg)."</td>";
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
                    echo"</tr>";
            }
            echo "</table><br>";
        }
        else
        {
            echo "<div class='table-title'>";
            echo "<h3>No results!<br>Please review your input !</h3>";
            echo "</div>";
        }
    }
    catch(Exception $e)
    {
        die(var_dump($e));
    }
}
else
{
    echo "<div class='table-title'>";
    echo "<h3>Error on search !<br>Please make sure that you had entered a valid input !</h3>";
    echo "</div>";
}
?>
<a href="home.php" id="back_to_home">Go Back</a>