<?php
    require_once('../server/connection.php');
    session_start();
    $stID = $_SESSION['id'];
    $sql = "SELECT `student_FIRST_NAME` , `student_LAST_NAME` FROM `student_record` WHERE `student_ID` = '${stID}'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    $studentName = $data['student_FIRST_NAME'] . " " . $data['student_LAST_NAME'] ;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image" href="img/apple-touch-icon.png">
</head>
<body>
    <div class="container container-fluid">
        <div class="jumbotron">
            <img src="img/elecdocom.png" alt="logo"><br><br><br>
            <h1 class='center'>Student Dashboard
            </h1>
            <small class="right">Welcome<h5><?php echo $studentName ?></h5></small>
        </div>
        <nav class="right">
            <form action="#" method="post">
                <?php
                    $sql="SELECT * FROM `project_notification` WHERE `student_id` = ${stID} AND `seen` = 0";
                    $result = $conn->query($sql);
                    $rows = $result->num_rows;
                    echo "<a href='./notification_screen.php' class='btn btn-outline-warning'>";
                    if($rows>=1){
                        $count = 0;
                        while($result->fetch_assoc()){$count+=1;}
                        echo "<font color='red'>".$count." New </font> ";
                    }
                    echo "Notifications</a>";
                ?>                
                &nbsp
                <a href="./all.php" class="btn btn-outline-dark">View All Proposals</a>           
                &nbsp
                <a href="./propose.php" class="btn btn-outline-success">Add New Proposal</a>
                &nbsp
                <input type="submit" value="Log Out" class="btn btn-outline-danger" name="logout">
            </form><br><br><br>
        </nav>
        <table class="table">
            <tr>
                <th>Proposal title</th>
                <th>Proposal date</th>
                <th>Authority</th>
                <th>Faculty assigned(If any)</th>
                <th>Proposal Status</th>
                <th>Proposal comments</th>
                <th>Proposal field</th>
                <th>Student semester</th>
                <th>Action</th>
            </tr>
            <?php
                $sql = "SELECT * FROM `project_record` WHERE `student_ID` = '${stID}';";
                $result = $conn->query($sql);
                $rows = $result->num_rows;
                if($rows >= 1){
                    while($data = $result->fetch_assoc()){
                        $title = $data['project_TITLE'];
                        $pdate = $data['project_date'];
                        $hodID = $data['hod_ID'];
                        $professor = $data['project_PROFESSOR'];
                        $status = $data['project_STATUS'];
                        $comment = $data['project_COMMENT'];
                        $course = $data['project_COURSE'];
                        $batch = $data['project_BATCH'];
                        echo"
                        <tr>
                            <td>${title}</td>
                            <td>${pdate}</td>
                        ";
                        $hodNAME = '';
                        $sql1 = "SELECT `hod_FIRST_NAME` , `hod_LAST_NAME` FROM `hod_record` WHERE `hod_ID` = '${hodID}'";
                        $result1 = $conn->query($sql1);
                        $data1 = $result1->fetch_assoc();
                        $hodNAME = $data1['hod_FIRST_NAME'] . ' ' . $data1['hod_LAST_NAME'];
                        echo"
                            <td>${hodNAME}</td>
                            <td>${professor}</td>
                        ";
                        if($status == 0){
                            echo "<td><font color='blue'><strong>PENDING</strong></font></td>";
                        }elseif($status == 1){
                            echo "<td><font color='green'><strong>ACCEPTED</strong></font></td>";
                        }else{
                            echo "<td><font color='red'><strong>REJECTED</strong></font></td>";
                        }
                        echo"
                            <td>${comment}</td>
                            <td>${course}</td>
                            <td>${batch}</td>
                            <td><a href='./../chat/index.php?hodID=${hodID}&stdID=${stID}' class='btn btn-primary'>Chat</a></td>
                        </tr>
                        ";
                    }
                }else{
                    echo '<br><br><br><br><tr><td colspan="9"><div class="alert alert-danger center" role="alert" >You Have No Project</div></td></tr>';
                }
            ?>
        </table>
    </div>
</body>
</html>
<?php
    if(isset($_POST['logout'])){
        session_unset();
        session_destroy();
        header("Location: ../");
    }
?>
