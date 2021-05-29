<?php
    require_once('database/db.php');
    
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'student') {
        session_destroy();
        header("location: login.php");
    }

    if (isset($_SESSION['id'])) {
        $id = (int) $_SESSION['id'];
        $group_id = null;
        $meeting_id = null;
        $sql_group_id = pg_query($conn, "select * from get_active_group($id);");
        while ($data = pg_fetch_assoc($sql_group_id)) { 
            $group_id = $data['group_id'];
            unset($data);
        }
        if (isset($group_id)) {
            $query = pg_query($conn, "select * from get_group_detils($group_id);");
            while ($data = pg_fetch_assoc($query)) { 
                $meeting_id = $data['meet_id'];
                unset($data);
            }
            // return var_dump($meeting_id);
            $sql = pg_query($conn, "select * from get_group_detils($group_id);");
            $meeting = pg_query($conn, "select * from edit_info_meeting($meeting_id);");
        } else {
            $_SESSION['msg'] = 'No scheduled group yet.';
            $_SESSION['insertUpdateFlag'] = 'ns-g';
            header("Location: dashboard.php");
        }
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
                <div class="alert alert-dark mt-3" role="alert">
                    <h5 class="alert-heading"> Study-groups at <i style="font-size: 1.2rem;"> <?php echo $res['place']; ?> </i>  between <i style="font-size: 1.2rem;"> <?php echo date('d-m-Y h:i',strtotime($res['start_date'])); ?> </i>  and <i style="font-size: 1.2rem;"> <?php echo date('d-m-Y h:i',strtotime($res['end_date'])); ?> </i> </h5>
                </div>
            <?php } ?>
            <table class="table table-bordered table-striped table-hoverable table-fullwidth">
                <tr>
                    <th>Topic</th>
                    <th>Description</th>
                    <th>Owner</th>
                    <th>Group Limit</th>
                    <th>Joined</th>
                    <th>Student Names</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($sql)) { ?>
                    <tr>
                        <td><?php echo $row['topic']; ?></td>
                        <td width="300"><?php echo $row['description']; ?></td>
                        <?php 
                            $groupId = $row['study_group_id'];
                            $ownerId = null;
                            $owner_sql = pg_query($conn, "select * from get_group_owner($groupId);");
                            while ($dataa = pg_fetch_assoc($owner_sql)) {
                                $ownerId = $dataa['stud_id'];
                        ?>
                            <td><?php echo $dataa['stud_name']; ?></td>
                        <?php } ?>
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
                        <td>
                            <?php 
                                if ($_SESSION['id'] == $ownerId) {
                            ?>
                                <a class="btn detail" href="edit_group.php?group_id=<?php echo $row['study_group_id']; ?>&meet_id=<?php echo $meeting_id ?>">
                                    <i class="material-icons dp48">border_color</i>
                                </a> |  <a class="btn waves-effect waves-light red lighten-2" href="leave_group.php?group_id=<?php echo $row['study_group_id']; ?>&stud_id=<?php echo $_SESSION['id'] ?>">
                                            <i class="material-icons dp48">remove_circle</i>
                                        </a>
                            <?php } else { ?>
                                <a class="btn waves-effect waves-light red lighten-2" href="leave_group.php?group_id=<?php echo $row['study_group_id']; ?>&stud_id=<?php echo $_SESSION['id'] ?>">
                                    <i class="material-icons dp48">remove_circle</i>
                                </a>
                            <?php } ?>
                        </td>                        
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>
</html>