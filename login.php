<?php
//for database connection
require_once('database/db.php');

//validate user using service
require_once('service/user_service.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $result = login_service($user, $password, $type);
    if (isset($result) && !empty($result)) {
        session_start();
        $_SESSION['loggedIn'] = true;
        $_SESSION['id'] = $result['id'];
        $_SESSION['name'] = $result['name'];
        $_SESSION['type'] = $result['type'];
        header('Location:dashboard.php');
    } else {
        echo '<script>
                alert("User or passwrod is invalid!!");
              </script>';
    }
}
?>



<html>

<head>
    <title>WorkTogetherMetting</title>
    <?php include 'sections/header.php'; ?>
    <?php include 'sections/script.php'; ?>
    <?php
        include 'sections/nav.php';
    ?>
    
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

        .input-field input[type=date]:focus+label,
        .input-field input[type=text]:focus+label,
        .input-field input[type=email]:focus+label,
        .input-field input[type=password]:focus+label {
            color: #e91e63;
        }

        .input-field input[type=date]:focus,
        .input-field input[type=text]:focus,
        .input-field input[type=email]:focus,
        .input-field input[type=password]:focus {
            border-bottom: 2px solid #e91e63;
            box-shadow: none;
        }
    </style>
</head>

<body>
    <div class="section m-5"></div>
    <main>
        <center>
            <h5>Welcome to WorkTogetherMetting</h5>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                    <form class="col s12" method="post">
                        <div class='row'>
                            <div class='col s12'>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input required class='validate' type='text' name='username' id='username' />
                                <label for='email'>Enter your username</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <input class='validate' required type='password' name='password' id='password' />
                                <label for='password'>Enter your password</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12 select-field'>
                            <select name="type" required>
                                <option value="" disabled selected>Choose type...</option>
                                <option value="admin">FSR</option>
                                <option value="student">Student</option>
                            </select>
                            <label>User Type</label>
                            </div>
                        </div>

                        <br />
                        <center>
                            <div class='row'>
                                <button type='submit' name='btn_login' class='col s12 btn waves-effect waves-light red lighten-2 submit-btn'>Login</button>
                            </div>
                        </center>
                    </form>
                </div>
            </div>
        </center>

        <div class="section"></div>
        <div class="section"></div>
    </main>
    <?php include 'sections/footer.php'; ?>
</body>

</html>

<script>
    $(document).ready(function(){

        // Initialise the select
        $('select').formSelect();
        // Create your validation helper text
        var validationMessage = '<span class="helper-text" data-error="Please choose an option"></span>';
        // Place it in the dom
        $('.select-wrapper input').after(validationMessage);
        // Error logic
        var select = jQuery('.select-wrapper input')[0];
        $('.submit-btn').on('click',function(){
            if ( jQuery('ul.select-dropdown li:not(.disabled).selected').length < 1 ) {
                $(select).addClass('invalid');
            }     
        });
    });
</script>