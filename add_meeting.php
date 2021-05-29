<?php
    require_once('database/db.php');
    
    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'admin') {
        session_destroy();
        header("location: login.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $place = $_POST["place"];
        $start_time = date('Y-m-d H:i:s', strtotime($_POST["start_date"]));
        $end_time = date('Y-m-d H:i:s', strtotime($_POST["end_date"]));
        $is_active = $_POST["visibility"];

        $sql = pg_query($conn, "select * from insert_update_meeting('$place', '$start_time', '$end_time', '$is_active', 'insert', '0');");

        while ($row = pg_fetch_assoc($sql)) {
            session_start();
            $pieces = explode(". ", $row['insert_update_meeting']);
            $_SESSION['msg'] = $pieces[0];
            $_SESSION['insertUpdateFlag'] = $pieces[1];
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
                                <input placeholder="Location" id="place" name="place" type="text" class="validate" required>
                                <label for="place" class="active">Location</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input type="datetime-local" name="start_date" id="start_date" required>
                                <label for="start_date" class="active">Start </label>
                            </div>
                            <div class="input-field col s6">
                                <input type="datetime-local" name="end_date" id="end_date" required>
                                <label for="end_date" class="active">End </label>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='input-field col s12 select-field'>
                            <select name="visibility">
                                <option selected value="f">Hide</option>
                                <option value="t">Active</option>
                            </select>
                            <label>Visibility</label>
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
<script>
    $(document).ready(function(){
        // Initialise the select
        $('select').formSelect();
    });
</script>
