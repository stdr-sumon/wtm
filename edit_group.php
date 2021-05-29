<?php
    require_once('database/db.php');
    
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'student') {
        session_destroy();
        header("location: login.php");
    }

    if (isset($_GET['group_id'])) {
        $id = $_GET['group_id'];
        $sql = pg_query($conn, "select * from edit_info_group($id);");
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $meeting_id = $_POST["meeting_id"];
        $subject = $_POST["topic"];
        $details = $_POST["description"];
        $student_limit = $_POST["limit"];
        $group_id = $_POST["group_id"];
        $stud_id = $_POST["stud_id"];

        $result = pg_query($conn, "select * from update_group_attribute($group_id,$stud_id,'$subject', '$details', $student_limit);");
        while ($row = pg_fetch_assoc($result)) {
            session_start();
            $pieces = explode(". ", $row['update_group_attribute']);
            $_SESSION['msg'] = $pieces[0];
            $_SESSION['insertUpdateFlag'] = $pieces[1];
            header("Location: student_meeting.php?id=$meeting_id");
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
    </style>
</head>
<body>
    <main>
        <div class="container">
            <div class="row">
                <div class="col s12 m-5">
                    <?php 
                        if(isset($_GET['group_id']) && isset($_GET['meet_id'])) {
                    ?>  
                        <form method="post">
                            <input name="stud_id" type="hidden" value="<?php echo $_SESSION['id'];?>">
                            <?php while ($row = pg_fetch_assoc($sql)) { ?>
                                <input name="meeting_id" type="hidden" value="<?php echo $_GET['meet_id'];?>">
                                <input name="group_id" type="hidden" value="<?php echo $_GET['group_id'];?>">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input placeholder="Topic" name="topic" type="text" class="validate" required value="<?php echo $row['sub'];?>">
                                        <label for="place" class="active">Topic</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input placeholder="Description" name="description" type="text" class="validate" required value="<?php echo $row['des'];?>">
                                        <label for="place" class="active">Description</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input placeholder="Student Limit" name="limit" type="number" class="validate" required value="<?php echo $row['stud_limit'];?>">
                                        <label for="place" class="active">Limit</label>
                                    </div>
                                </div>

                                <div class='row'>
                                    <button type='submit' name='btn_login' class='col s12 btn waves-effect waves-light lighten-2 submit-btn'>Update</button>
                                </div>
                            <?php } ?>
                        </form>
                    <?php 
                        } else {
                            header("Location: dashboard.php");
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>
</html>