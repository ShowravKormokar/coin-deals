<?php
SESSION_START();
require("db.php");

$adminID="";
$adminName="N/A";
$logStatus="Login";

if(isset($_SESSION['email']))
{
    $email=$_SESSION['email'];
    $pass=$_SESSION['password'];
    $admin=mysqli_query($conn,"select * from admin where adminEmail='$email' and adminPass='$pass'");
    $u=mysqli_fetch_assoc($admin);
    if($u)
    {
        $adminID=$u['adminID'];
        $adminName=$u['adminName'];
        $logStatus="Logout";
        
    }
    else{
        
        header("location:signIn.php");
    }
} else{
    header("location:signIn.php");
}

//Logout
if(isset($_POST['logout']))
{
    session_start();
    session_unset();
    session_destroy();
    header("location:/phnBook/signIn.php");
}

// --------------- Add agents

//Error variables
$nameErr="";
$phnErr="";
$insertErr="";
$emailErr="";
$passErr="";

//Alert functions
function function_alert($message) {
        echo "<script>alert('$message');
        </script>";
}

if(isset($_POST['addAgent']))
{
    // echo "Done!";
    if($_POST['afname'] == "")
    {
        $nameErr="Must enter the first name.";
    }else{
        if(strlen($_POST['afname'])<=20 || strlen($_POST['alname'])<=20){
            $afname=$_POST['afname'];
            $alname=$_POST['alname'];
        }else{
            $nameErr="First name or Last name maximum length 20 character only.";
        }
    }
    
    if($_POST['aphn'] == "")
    {
        $phnErr="Must enter the phone number.";
    }else{
        if(!empty($_POST['aphn'])){

            $aphn=$_POST['aphn'];
        }else{
            $phnErr="Wrong number, must be integer number.";
        }
    }
    if (filter_var($_POST['aemail'], FILTER_VALIDATE_EMAIL)) {
    $inEmail = $_POST['aemail'];
    } else {
        $emailErr = "Incorrect email format.";
    }

    if($_POST['apass'] == "" || $_POST['aconpass']==""){
        $passErr="Please enter password";
    }else{
        if($_POST['apass'] ==$_POST['aconpass']){
            
        }
    }

    $anote=$_POST['anote'];
    $astatus=$_POST['astatus'];
    // echo "$afname"." + "."$aphn";
    
    if(empty($afname) || empty($aphn))
    {
        echo "NOT DONE!";
        $insertErr="Must fill name and phone field.";
    } else{
    $push=mysqli_query($conn,"insert into agents(aFName,aLName,aPHN,aEmail,aStatus,aNote) values('$afname','$alname','$aphn','$inEmail','$astatus','$anote')");
    echo "DONE!";
        if($push){
            function_alert("New contact added.");
            unset($afname,$alname,$aphn,$inEmail,$astatus,$anote);
            unset($push);
        }
    }
        
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
    <title>BetBazzi-Admin</title>
</head>
<body>
<header class="header"> 
        <div class="navbar">
            <div class="logo"><a href="http://localhost/website/admin.php">C<span>asin</span>O</a></div>
        </div>
        <div class="nav-links">
            <a href="#home" class="link">Home</a>
            <a href="#agents" class="link">Agents</a>
            <a href="#users" class="link">Users</a>
            <a href="#history" class="link">History</a>
        </div>
        <div class="log-details">
                <p class="admin-name"><?php echo "$adminName"; ?></p>
                    <input type="submit" name="logout" class="btn ad-btn" value="Logout">
                </form>
        </div>
    </header>
    <section class="admin-notice">
        <div class="notice">
            <marquee behavior="slide" direction="left" loop="5">📢This site is under construction.🚨 || 📢This site is under construction.🚨 || 📢This site is under construction.🚨</marquee>
        </div>
    </section>
    <section class="balance-panel" id="home">
        <div class="balance-container">
            <div class="balance-card special-card">
                <p class="balance-title special-title">
                    Total Avail. Bal.
                </p>
                <h2 class="balance-show">
                    🪙99999999.00
                </h2>
            </div>
            <div class="balance-card">
                <p class="balance-title">
                    Total Diposit Bal.
                </p>
                <h2 class="balance-show">
                    🪙10000.00
                </h2>
            </div>
            <div class="balance-card">
                <p class="balance-title">
                    Total Agents Bal.
                </p>
                <h2 class="balance-show">
                    🪙10000.00
                </h2>
            </div>
            <div class="balance-card">
                <p class="balance-title">
                    Total Users Bal.
                </p>
                <h2 class="balance-show">
                    🪙100.00
                </h2>
            </div>
            <div class="balance-card">
                <p class="balance-title">
                    Total Agents
                </p>
                <h2 class="balance-show">
                    👨‍✈️20
                </h2>
            </div>
            <div class="balance-card">
                <p class="balance-title">
                    Total Users
                </p>
                <h2 class="balance-show">
                    🙎‍♂️1000
                </h2>
            </div>
        </div>
    </section>

    <section class="addAgents">
        <h1 class="heading" onclick="toggleForm()">Add New Agent</h1>
        <form method="POST" action="">
            <label for="fname">First Name:</label>
            <input type="text" name="afname" placeholder="abc">

            <label for="lname">Last Name:</label>
            <input type="text" name="alname" placeholder="xyz">

            <label for="phn">Phone No.:</label>
            <input type="number" name="aphn" placeholder="0123456789">

            <label>Email:</label>
            <input type="email" name="aemail" placeholder="abc@example.com">
            <span class="error"> <?php echo "$emailErr"; ?> </span>

            <!-- Select Company -->
            <label for="status">Status:</label>
            <select name="astatus" id="status">
                <option value="1">Activate</option>
                <option value="0">Deactivate</option>
            </select><br>

            <label for="address">Note:</label>
            <textarea name="anote" rows="4" cols="4" placeholder="Note or information"></textarea>

            <label>Password:</label>
            <input class="pass" type="password" name="apass" placeholder="">
            <span class="error"> <?php echo "$passErr"; ?> </span>

            <label>Confrim Password:</label>
            <input class="pass" type="password" name="aconpass" placeholder="">
            <span class="error"> <?php echo "$passErr"; ?> </span>

            <!-- Error messages -->
            <span class="error"> <?php echo "$insertErr"; ?> </span>

            <!-- Submit Button -->
            <input class="btn add-btn" type="submit" name="addAgent" value="Add agents"><br>
        </form>
    </section>

    <section class="balance-panel">      
        <div class="agents-container">
             <table class="agent-list" border="1">
                <tr>
                    <th>Agent ID</th>
                    <th>Name</th>
                    <th>Phn. no.</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th>Total Users</th>
                    <th>Total Bal.</th>
                    <th>Action</th>
                </tr>
            <?php
                $result=mysqli_query($conn,"select * from agents");
                while($con=mysqli_fetch_assoc($result)){
                    echo '<tr>
                            <td>'.$con['agentID'].'</td>
                            <td>'.$con['aFName'].' '.$con['aLName'].'</td>
                            <td>'.$con['aPHN'].'</td>
                            <td>'.$con['aEmail'].'</td>
                            <td>'.$con['aStatus'].'</td>
                            <td>'.$con['aNote'].'</td>
                            <td>'.$con['aNote'].'</td>
                            <td>'.$con['aNote'].'</td>
                            <td><form class="action" method="POST">
                                    <input type="hidden" name="delete" value="'.$con['agentID'].'" />
                                    <input type="submit" name="delBtn" class="btn ad-btn" value="Delete"><a class="btn ad-btn" href="update.php?edit='.$con['agentID'].'">Edit</a>
                                </form>
                            </td>
                        </tr>
                    </table>';
                }
            ?>
        </div>
    </section>
    <script>
        function toggleForm() {
            var formSection = document.querySelector('.addAgents');
            formSection.classList.toggle('expanded');
        }
        function toggleCard(card) {
            card.classList.toggle('expanded');
        }
    </script>
</body>
</html>