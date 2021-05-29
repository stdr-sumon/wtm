<nav>
    <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 'admin') { ?>
        <div class="nav-wrapper">
            <p class="brand-logo center">Welcome <?php echo isset($_SESSION['name']) ? ucfirst ($_SESSION['name']) : '';?>
            </p>
            <a class="right m-3 logout" href="../wtm/logout.php"><i class="material-icons left" style="margin-top: -35% !important; margin-left: 15% !important;">power_settings_new</i>
            </a> 
            <ul class="left hide-on-med-and-down">
                <li><a href="../wtm/dashboard.php">Home</a></li>
                <li><a href="../wtm/student_list.php">Students</a></li>
            </ul>
        </div>
    <?php } elseif(isset($_SESSION['type']) && $_SESSION['type'] == 'student') { ?>
        <div class="nav-wrapper">
            <a class="brand-logo center student_logo" href="../wtm/student_profile.php">Welcome <?php echo isset($_SESSION['name']) ? ucfirst ($_SESSION['name']) : '';?></a>
            <a class="right m-3 logout" href="../wtm/logout.php"><i class="material-icons left" style="margin-top: -35% !important; margin-left: 15% !important;">power_settings_new</i>
            </a> 
            <ul class="left hide-on-med-and-down">
                <li><a href="../wtm/dashboard.php">Home</a></li>
                <li><a href="../wtm/student_scheduled.php">Scheduled</a></li>
                <li><a href="../wtm/student_profile.php">Profile</a></li>
            </ul>
        </div>
    <?php } else {?>
        <div class="nav-wrapper">
            <p class="brand-logo center">Welcome to WorkTogetherMeeting </p>
        </div>
    <?php } ?>
</nav>