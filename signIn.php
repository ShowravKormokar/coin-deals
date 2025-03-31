<?php
session_start();
require("db.php");
$logEmailErr="";
$logPassErr="";
$loginErr="";
if(isset($_POST['login']))
{
    
    if($_POST['log_email'] == "")
    {
        $logEmailErr="Please enter the email.";
    }else{
        if (filter_var($_POST['log_email'], FILTER_VALIDATE_EMAIL)) {
    $log_email = $_POST['log_email'];
} else {
    $logEmailErr = "Incorrect email format.";
}

    }
    
    if($_POST['log_pass'] == "")
    {
        $logPassErr="Please enter the password.";
    }else{
        $log_pass=$_POST['log_pass'];
    }
    
    $admin=mysqli_query($conn,"select * from admin where adminEmail='$log_email' and adminPass='$log_pass'");
    if(mysqli_fetch_assoc($admin))
    {
        //echo "You are logged in.";
        $_SESSION['email']=$log_email;
        $_SESSION['password']=$log_pass;
        
        header("location:/website/admin.php");
    }
    else{
        if(empty($logEmailErr) && empty($logPassErr)){
            // echo $admin['adminEmail']. '<br>';
            $loginErr="Incorrect email or password ";
        }
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <style>
        .signinB {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
        }

        .signinB .signIn {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .signIn .heading {
            font-size: 24px;
            color: lightblue;
            margin-bottom: 20px;
            text-align:center;
        }

       .signIn form {
            display: flex;
            flex-direction: column;
            gap: 0px;
        }
        .signIn form a{
        font-size:10px;
            text-align:center;
            margin-bottom:3px;
        }

       .signIn lebel {
            font-size: 14px;
            color: #555;
        }

        .signIn input[type="email"],
        input[type="password"] {
            padding: 12px;
            padding-top:5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

       .signIn input[type="submit"] {
            padding: 12px;
            background-color: lightblue;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signIn input[type="submit"]:hover {
            background-color: #4d90fe;
        }

        .signIn .error {
            color: red;
            font-size: 12px;
            margin-bottom: 5px;
        }
        @media screen and (max-width: 768px) {
            .signIn {
                padding: 30px;
            }

            .heading {
                font-size: 20px;
            }

            input[type="email"],
            input[type="password"],
            input[type="submit"] {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 480px) {
            .signIn {
                padding: 25px;
            }

            .heading {
                font-size: 18px;
            }

            input[type="email"],
            input[type="password"],
            input[type="submit"] {
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="signinB">
<section class="signIn">
    <h1 class="heading">Sign In</h1><br>
    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="log_email" placeholder="abc@example.com">
        <span class="error"><?php echo "$logEmailErr"; ?></span>
        <label>Password:</label>
        <input type="password" name="log_pass" placeholder="........">
        <span class="error"><?php echo "$logPassErr"; ?></span>
        <a href="signUp.php">No account? Register.</a>
        <span class="error"><?php echo "$loginErr"; ?></span>
        <input type="submit" name="login" value="Login Now"><br>
    </form>
</section>

</body>
</html>

