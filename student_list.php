<?php
    require_once('database/db.php');

    session_start();
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'admin') {
        session_destroy();
        header("location: login.php");
    }

    $sql = pg_query($conn, "select * from get_student()");
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
                if(isset($_SESSION['msg'])) {
                    $message = $_SESSION['msg'];
            ?>
            <?php
                if ($_SESSION['insertUpdateFlag'] == 'stud_add') {
            ?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-success" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } elseif ($_SESSION['insertUpdateFlag'] == 'stud_null') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-danger" role="alert">
                            <?php  echo $message; ?>
                        </div>
                    </div>
            <?php } elseif ($_SESSION['insertUpdateFlag'] == 'stud_up') {?>
                    <div class="messagealert mt-3">
                        <div class="alert alert-success" role="alert">
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

            <a class="btn right m-3" href="../wtm/add_student.php">
                <i class="fa fa-plus" aria-hidden="true"></i> New Student
            </a>

            <table class="table table-bordered table-striped table-hoverable table-fullwidth mt-2">
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Joined On</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($sql)) { ?>
                    <tr>
                        <td><?php echo $row['stud_name']; ?></td>
                        <td><?php echo $row['stud_username']; ?></td>
                        <td><?php echo date('d-m-Y h:i',strtotime($row['joined_on']));  ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>

</html>
