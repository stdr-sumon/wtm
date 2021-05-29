<?php
    require_once('database/db.php');
    
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'admin') {
        session_destroy();
        header("location: login.php");
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = pg_query($conn, "select * from get_meeting_detils('$id');");
        $meeting = pg_query($conn, "select * from edit_info_meeting('$id');");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkTogetherMetting</title>
    <?php include 'sections/nav.php'; ?>
    <?php include 'sections/script.php'; ?>
    <?php include 'sections/header.php'; ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom-style.css">
</head>
<body>
    <main>
        <div class="container">
            <?php while ($res = pg_fetch_assoc($meeting)) { ?>
                <div class="alert alert-info mt-3" role="alert">
                    <h5 class="alert-heading"> Study-groups at <i style="font-size: 1.2rem;"> <?php echo $res['place']; ?> </i>  between <i style="font-size: 1.2rem;"> <?php echo date('d-m-Y h:i',strtotime($res['start_date'])); ?> </i>  and <i style="font-size: 1.2rem;"> <?php echo date('d-m-Y h:i',strtotime($res['end_date'])); ?> </i> </h5>
                </div>
            <?php } ?>
            <table class="table table-bordered table-striped table-hoverable table-fullwidth">
                <tr>
                    <th>Topic</th>
                    <th>Description</th>
                    <th>Group Limit</th>
                    <th>Joined</th>
                    <th>Student Names</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($sql)) { ?>
                    <tr>
                        <td><?php echo $row['topic']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['student_limit']; ?></td>
                        <td><?php echo $row['no_of_student']; ?></td>
                        <td>
                            <?php 
                                if (!empty($row['joined_stuents'])) {
                                    $splittedStudent = str_replace(array( '{', '}' ), '', $row['joined_stuents']);
                                    $splittedStudent = explode(',', $splittedStudent);
                                    $check = 0;
                                    foreach ($splittedStudent as $data) { 
                                        $check++;
                                        echo $data;
                                        if ($check < count($splittedStudent)) {
                                            echo ', ';
                                        }
                                    }
                                } else {
                                    echo '-';
                                }
                            ?>
                        </td>
                        
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>
</html>