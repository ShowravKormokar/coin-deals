<?php
session_start();
require("db.php");
$regAdminNameErr="";
$regEmailErr="";
$regPassErr="";
$regErr="";
if(isset($_POST['registration']))
{
    if($_POST['reg_AdminName'] == "")
    {
        $regAdminNameErr="Please enter the admin name.";
    }else{
        if(strlen($_POST['reg_AdminName'])<=20){
            $reg_AdminName=$_POST['reg_AdminName'];
        }else{
            $regAdminNameErr="Maximum length 20 character only.";
        }
    }
    if($_POST['reg_email'] == "")
    {
        $regEmailErr="Please enter the email.";
    }else{
        if (filter_var($_POST['reg_email'], FILTER_VALIDATE_EMAIL)) {
    $reg_email = $_POST['reg_email'];
} else {
    $regEmailErr = "Incorrect email format.";
}

    }
    
    if($_POST['reg_pass'] == "")
    {
        $regPassErr="Please enter the password.";
    }else{
        $reg_pass=$_POST['reg_pass'];
    }
    
    if(empty($reg_AdminName)||empty($reg_email)||empty($reg_pass))
    {
        $regErr="Fill all the field.";
    }
    else
    {
        $Admin=mysqli_query($conn,"select * from admin where adminEmail='$reg_email'");
    if(mysqli_fetch_assoc($Admin))
    {
        $loginErr="This email already exists. Try another.";
    }
    else{
        if($reg=mysqli_query($conn,"insert into admin(adminName,adminEmail,adminPass) values('$reg_AdminName','$reg_email','$reg_pass')")){
            $_SESSION['email']=$reg_email;
            $_SESSION['password']=$reg_pass;
        header("location:/website/admin.php");
   
        } else{
            $regErr="Something wrong";
        }
             }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f1f1;
        }

        .signUp {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .signUp .heading {
            font-size: 24px;
            color: lightblue;
            margin-bottom: 20px;
            text-align:center;
        }

       .signUp form {
            display: flex;
            flex-direction: column;
            gap: 0px;
        }
        .signUp form a{
        font-size:10px;
            text-align:center;
            margin-bottom:3px;
        }

       .signUp lebel {
            font-size: 14px;
            color: #555;
        }

        .signUp input[type="email"],
        input[type="password"], input[type="text"] {
            padding: 12px;
            padding-top:5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

       .signUp input[type="submit"] {
            padding: 12px;
            background-color: lightblue;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signUp input[type="submit"]:hover {
            background-color: #4d90fe;
        }

        .signUp .error {
            color: red;
            font-size: 12px;
            margin-bottom: 5px;
        }
        @media screen and (max-width: 768px) {
            .signUp {
                padding: 30px;
            }

            .heading {
                font-size: 20px;
            }
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="submit"] {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 480px) {
            .signUp {
                padding: 25px;
            }

            .heading {
                font-size: 18px;
            }
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="submit"] {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<section class="signUp">
    <h1 class="heading">Sign Up</h1><br>
    <form method="POST" action="">
        <lebel>Admin Name:</lebel>
        <input type="text" name="reg_AdminName" placeholder="abc"><br>
        <span class="error"> <?php echo "$regAdminNameErr"; ?> </span>
        <lebel>Email:</lebel>
        <input type="email" name="reg_email" placeholder="abc@example.com"><br>
        <span class="error"> <?php echo "$regEmailErr"; ?> </span>
        <lebel>Password:</lebel>
        <input type="password" name="reg_pass" placeholder="........"><br>
         <span class="error"> <?php echo "$regPassErr"; ?> </span>
          <a href="signIn.php">Already have an account, login!</a>
         <span class="error"> <?php echo "$regErr"; ?> </span>
        <input type="submit" name="registration" value="Register Now"><br>
    </form>
    
</section>
</body>
</html>
