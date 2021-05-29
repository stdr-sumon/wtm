<?php
    require_once('database/db.php');
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'student') {
        session_destroy();
        header("location: login.php");
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = pg_query($conn, "select * from get_meeting_detils($id);");
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
            <?php 
                if(isset($_SESSION['msg'])) {
                    $message = $_SESSION['msg'];
            ?>
            <?php
                if ($_SESSION['insertUpdateFlag'] == '1') {
            ?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-success" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } elseif ($_SESSION['insertUpdateFlag'] == '2') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-danger" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } elseif ($_SESSION['insertUpdateFlag'] == 'gu1') {?>
                <div class="messagealert mt-3">
                    <div class="alert alert-success" role="alert">
                        <?php  echo $message; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['insertUpdateFlag'] == 'gu2') {?>
                <div class="messagealert mt-3">
                    <div class="alert alert-danger" role="alert">
                        <?php  echo $message; ?>
                    </div>
                </div>

                <?php } elseif ($_SESSION['insertUpdateFlag'] == 'jg-1') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-success" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
                <?php } elseif ($_SESSION['insertUpdateFlag'] == 'jg-2') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-info" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } else {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-warning" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } ?>

            <?php 
                    unset($_SESSION['msg']);
                    unset($_SESSION['insertUpdateFlag']);
                    unset($message);
                }
            ?>
            <a class="btn right m-3" href="add_group.php?id=<?php echo $_GET['id']; ?>">
                <i class="fa fa-plus" aria-hidden="true"></i> New Group
            </a>
            <div class="row messagealert">
                <h6 class="alert alert-info" role="alert">Only joinable study groups are visibled.</h6>
            </div>

            <table class="table is-success is-bordered is-striped is-narrow is-hoverable is-fullwidth mt-2">
                <tr>
                    <th>Group-Code</th>
                    <th>Owner</th>
                    <th>Topic</th>
                    <th  width="300">Description</th>
                    <th>Limit</th>
                    <th>Joined</th>
                    <th>Student</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($sql)) { ?>
                    <?php 
                        $groupId = $row['study_group_id'];
                        $ownerId = null;
                        $groupOwner = null;
                        $owner = pg_query($conn, "select * from get_group_owner($groupId);");
                        while ($data = pg_fetch_assoc($owner)) {
                            $ownerId = $data['stud_id'];
                            $groupOwner = $data['stud_name'];
                        }

                        $loggedUser = $_SESSION['id'];
                        $loggedUserGroup = null;
                        $loggedUserGroupSql = pg_query($conn, "select * from get_logged_group($loggedUser);");
                        while ($res = pg_fetch_assoc($loggedUserGroupSql)) {
                            $loggedUserGroup = $res['grp_id'];
                        }

                    ?>
                    <?php if (($row['student_limit'] > $row['no_of_student']) || ($_SESSION['id'] == $ownerId) || ($loggedUserGroup == $row['study_group_id'])) { ?>
                        <tr>
                            <td> G-<?php echo substr(md5($row['study_group_id']), 0, 8); ?> </td>
                            <td><?php echo $groupOwner; ?></td>
                            <td><?php echo $row['topic']; ?></td>
                            <td width="300"><?php echo $row['description']; ?></td>
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
                                    if (($_SESSION['id'] == $ownerId) && ($loggedUserGroup == $row['study_group_id'])) {
                                ?>
                                    <a class="btn detail" href="edit_group.php?group_id=<?php echo $row['study_group_id']; ?>&meet_id=<?php echo $_GET['id'] ?>">
                                        <i class="material-icons dp48">border_color</i>
                                    </a> |
                                    <a class="btn waves-effect waves-light red lighten-2" href="leave_group.php?group_id=<?php echo $row['study_group_id']; ?>&stud_id=<?php echo $_SESSION['id'] ?>">
                                        <i class="material-icons dp48">remove_circle</i>
                                    </a>
                                <?php } elseif(($_SESSION['id'] != $ownerId) && ($loggedUserGroup == $row['study_group_id'])) { ?>
                                    <a class="btn waves-effect waves-light red lighten-2" href="leave_group.php?group_id=<?php echo $row['study_group_id']; ?>&stud_id=<?php echo $_SESSION['id'] ?>">
                                        <i class="material-icons dp48">remove_circle</i> 
                                    </a>
                                <?php } else { ?>
                                    <a class="btn" href="join_new_group.php?group_id=<?php echo $row['study_group_id']; ?>&stud_id=<?php echo $_SESSION['id'] ?>&meet_id=<?php echo $_GET['id'] ?>">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
                                <?php } ?>
                            </td> 
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>
</html>