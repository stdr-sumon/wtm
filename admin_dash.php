<?php
    require_once('database/db.php');
    
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'admin') {
        session_destroy();
        header("location: login.php");
    }

    $sql = pg_query($conn, "select * from get_meeting_overview()");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        body {
            background: #fff;
        }
        .detail {
            background-color: #26A6D6;
        }
    </style>
</head>

<body>
    <main>
        <div class="container">
            <?php 
                if(isset($_SESSION['message'])) {
                    $message = $_SESSION['message'];
                    // return print_r($_SESSION);
            ?>
            <?php
                if ($_SESSION['flag'] == '1') {
            ?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-success" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } else {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-danger" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } ?>
            <?php 
                    unset($_SESSION['message']);
                    unset($_SESSION['flag']);
                    unset($message);
                }
            ?>

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
            <?php } elseif ($_SESSION['insertUpdateFlag'] == '3') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-success" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } elseif ($_SESSION['insertUpdateFlag'] == '4') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-danger" role="alert">
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

            <a class="btn right m-3" href="../wtm/add_meeting.php">
                <i class="fa fa-plus" aria-hidden="true"></i> New Meeting
            </a>

            <table class="table table-bordered table-striped table-hoverable table-fullwidth mt-2">
                <tr>
                    <th>Place</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Visibility </th>
                    <th>Groups </th>
                    <th>Joined Student</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($sql)) { ?>
                    <tr>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo date('d-m-Y h:i',strtotime($row['start_time']));  ?></td>
                        <td><?php echo date('d-m-Y h:i',strtotime($row['end_time']));  ?></td>
                        <td>
                        <?php 
                            date_default_timezone_set("Europe/BERLIN");
                            if (strtotime(date('Y-m-d H:i:s', time())) < strtotime(date('Y-m-d H:i:s', strtotime($row['end_time'])))) {
                        ?>
                            <div class="switch">
                                <label>
                                    <input class="visibility" type="checkbox" <?php echo ($row['is_active'] != 'f') ? 'checked' : '';?> data-id="<?php echo $row['id']; ?>" data-value="<?php echo $row['is_active']; ?>">
                                    <span class="lever"></span>
                                </label>
                            </div>
                        <?php 
                            } else {
                        ?>
                            Hidden (Finished)
                        <?php }?>
                        </td>
                        <td><?php echo $row['no_of_groups']; ?></td>
                        <td><?php echo $row['no_of_student']; ?></td>
                        <td> 
                            <a class="btn detail" href="meeting_detils.php?id=<?php echo $row['id']; ?>">
                                <i class="material-icons dp48">airplay</i>
                            </a> | 
                            
                            <a class="btn" href="edit_meeting.php?id=<?php echo $row['id']; ?>">
                                <i class="material-icons dp48">border_color</i>
                            </a> 
                            

                            <?php
                                if ($row['is_active'] != 't') {
                            ?>
                                    | <a class="btn waves-effect waves-light red lighten-2" href="delete_meeting.php?id=<?php echo $row['id']; ?>">
                                        <i class="fa fa-times" aria-hidden="true"></i>
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

<script>
    'use strict';
    $.noConflict();
    $(document).ready(function () {
        $(document).on('click',".visibility",function () {
        var meetingId =$(this).attr('data-id');
        var is_active = $(this).is(':checked') ? 1 : 0;
        var url   = 'change_visibility_ajax.php';

        $.ajax({
            url:url,
            method:"POST",
            data:{
            meetingId:meetingId, 
            is_active:is_active
            },
            dataType:"json",
            success:function(data) {
                // console.log(data);
            }
            });
        });
    });
</script>