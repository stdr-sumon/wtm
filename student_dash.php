<?php
    require_once('database/db.php');
    if (isset($_SESSION['type']) && $_SESSION['type'] != 'student') {
        session_destroy();
        header("location: login.php");
    }

    $sql = pg_query($conn, "select * from get_meeting_overview_student()");
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
                    if ($_SESSION['insertUpdateFlag'] == 'ns-g') {
                ?>
                        <div class="messagealert mt-3">
                            <div class="alert alert-info" role="alert">
                                <?php  echo $message; ?>
                            </div>
                        </div>
                <?php } ?>
            <?php } 
                unset($_SESSION['msg']);
            ?>
            <table class="table table-bordered table-striped table-hoverable table-fullwidth mt-2">
                <tr>
                    <th>Meeting-Code</th>
                    <th>Place</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>No. of Groups</th>
                </tr>
                <?php while ($row = pg_fetch_assoc($sql)) { ?>
                    <tr>
                        <td> <a href="student_meeting.php?id=<?php echo $row['m_id']; ?>"> M-<?php echo substr(md5($row['m_id']), 0, 8); ?> </a> </td>
                        <td><?php echo $row['m_place']; ?></td>
                        <td><?php echo date('d-m-Y h:i',strtotime($row['start_time']));  ?></td>
                        <td><?php echo date('d-m-Y h:i',strtotime($row['end_time']));  ?></td>
                        <td><?php echo $row['no_of_groups']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>
</html>