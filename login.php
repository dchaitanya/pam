<?php
    session_start();

    if (isset($_SESSION['is_logged']) && $_SESSION['is_logged']) {
        header("Location: index.php");
    }

    $file = 'login_log.txt';
    
    #file_put_contents($file, "Start of login page\n", FILE_APPEND);
    $error_msg = '';
    if (isset($_POST['login'])) {
        #file_put_contents($file, "Post Request Found\n", FILE_APPEND);
        $redirect = "index";
        if (isset($_GET['redirect'])) {
            $redirect = $_GET['redirect'];
        }
        $username = $_POST['username'];
        $password = $_POST['password'];

        include_once("db_connect.php");
        $user_query = "select firstname, lastname, is_super from auth_users where is_active = '1' and username='$username' and password=sha1('$password')";

        $db = new db();
        $user_rs = $db->query($user_query);

        if (mysqli_num_rows($user_rs)) {
            session_start();

            $user_rec = mysqli_fetch_object($user_rs);
            $firstname = $user_rec->firstname;
            $lastname = $user_rec->lastname;
            $is_super = $user_rec->is_super;

            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['is_super'] = $is_super;
            $_SESSION['is_logged'] = true;
            
            #file_put_contents($file, "User is Validated... trying to redirect to $redirect\n", FILE_APPEND);

            header("Location: $redirect.php");

        } else {
            #file_put_contents($file, "User validation failed\n", FILE_APPEND);
            $error_msg = "Invalid Username or Password.";
        }
    }
    #file_put_contents($file, "Get Request Found\n", FILE_APPEND);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Log In | Personal Accounting Management</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link type="text/css" rel="stylesheet" href="./media/css/main.css" />
<script type="text/javascript" src="./media/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#username').focus();
});
</script>
</head>
<body>
<div id="header">
<div id="header-top"></div>
<div id="login">
<form action="" method="POST">
    <table align="center">
		<tr>
			<th colspan="2" align="center">
				<img class="logo" border="0" alt="PERSONAL ACCOUNTING MANAGEMENT" src="./media/images/logo.png" />
			</th>
        <tr height="25px">
            <th colspan="2" align="left">Login</th>
        </tr>
        <tr height="25px">
            <th align="left">Username:</th>
            <td align="left"><input type="text" name="username" id="username" value="" /></td>
        </tr>
        <tr height="25px">
            <th align="left">Password:</th>
            <td align="left"><input type="password" name="password" id="password" value="" /></td>
        </tr>
        <tr height="25px">
            <th colspan="2" align="center"><input type="submit" name="login" value="Login" /></th>
        </tr>
        <tr height="25px">
            <th colspan="2" align="center">
            <?php
                if ($error_msg) {
                    echo '<lable style="color:#FF0000">'.$error_msg.'</lable>';
                }
            ?>
            </th>
        </tr>
    </table>
</form>
</div> <!-- END of div login -->
<?php include("footer.php")?>
