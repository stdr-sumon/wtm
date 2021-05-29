<?php
    require_once('database/db.php');
    
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'admin') {
        session_destroy();
        header("location: login.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $name = $_POST["stud_name"];
        $user = $_POST["stud_user"];
        $pass = $_POST["stud_pass"];
        $insert = 0;

        $sql = pg_query($conn, "select * from insert_update_student('$name', '$user', '$pass', 'insert', $insert);");

        while ($row = pg_fetch_assoc($sql)) {
            session_start();
            $pieces = explode(". ", $row['insert_update_student']);
            $_SESSION['msg'] = $pieces[0];
            $_SESSION['insertUpdateFlag'] = $pieces[1];
            header("Location: student_list.php");
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
                  <form method="post">
                        <div class="row">
                            <div class="input-field col s12">
                                <input placeholder="Student Name" id="place" name="stud_name" type="text" class="validate" required>
                                <label for="place" class="active">Name</label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col s12">
                                <input placeholder="Username" id="place" name="stud_user" type="text" class="validate" required>
                                <label for="place" class="active">Username</label>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' required type='password' name='stud_pass' id='password' />
                                <label for='password'>Enter password</label>
                            </div>
                        </div>

                        <div class='row'>
                            <button type='submit' name='btn_login' class='col s12 btn waves-effect waves-light lighten-2 submit-btn'>Submit</button>
                        </div>
                    </form>
                
                </div>
            </div>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>
</html>