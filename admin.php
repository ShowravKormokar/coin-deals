<?php
session_start();
require("db.php");

// Initialize variables
$adminID = "";
$adminName = "N/A";
$logStatus = "Login";

// Check if admin is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $pass = $_SESSION['password'];

    $admin = mysqli_query($conn, "SELECT * FROM admin WHERE adminEmail='$email' AND adminPass='$pass'");
    $u = mysqli_fetch_assoc($admin);

    if ($u) {
        $adminID = $u['adminID'];
        $adminName = $u['adminName'];
        $logStatus = "Logout";
    } else {
        header("Location: signIn.php");
        exit();
    }
} else {
    header("Location: signIn.php");
    exit();
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: /coin-deals/signIn.php");
    exit();
}

// Error variables
$nameErr = $phnErr = $insertErr = $emailErr = $passErr = "";

// Alert function
function function_alert($message)
{
    echo "<script>alert('$message');</script>";
}

// Add Agent Form Submission
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addAgent'])) {
    $afname = $_POST['afname'] ?? "";
    $alname = $_POST['alname'] ?? "";
    $aphn = $_POST['aphn'] ?? "";
    $inEmail = $_POST['aemail'] ?? "";
    $apass = $_POST['apass'] ?? "";
    $aconpass = $_POST['aconpass'] ?? "";
    $anote = $_POST['anote'] ?? "";
    $astatus = $_POST['astatus'] ?? "";

    if (empty($afname)) {
        $nameErr = "Must enter the first name.";
    } elseif (strlen($afname) > 20 || strlen($alname) > 20) {
        $nameErr = "First name or last name cannot exceed 20 characters.";
    }

    if (empty($aphn)) {
        $phnErr = "Must enter the phone number.";
    } elseif (!is_numeric($aphn)) {
        $phnErr = "Phone number must be numeric.";
    }

    if (!filter_var($inEmail, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Incorrect email format.";
    }

    if (empty($apass) || empty($aconpass)) {
        $passErr = "Please enter a password.";
    } elseif ($apass !== $aconpass) {
        $passErr = "Passwords do not match.";
    }

    // If no errors, insert data
    if (empty($nameErr) && empty($phnErr) && empty($emailErr) && empty($passErr)) {
        $push = mysqli_query($conn, "INSERT INTO agents (aFName, aLName, aPHN, aEmail, aStatus, aNote, agConPass) 
            VALUES ('$afname', '$alname', '$aphn', '$inEmail', '$astatus', '$anote', '$apass')");

        if ($push) {
            function_alert("New contact added.");
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent form resubmission
            exit();
        } else {
            $insertErr = "Database error: Unable to add agent.";
        }
    }
}

// Fetch total agents count
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM agents");
$row = mysqli_fetch_assoc($result);
$totalAgent = $row['total'];

// Fetch agents account data
$agAccData = mysqli_query($conn, "SELECT * FROM agentsacc");
$agAcc = mysqli_fetch_assoc($agAccData);


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
            <div class="logo"><a href="http://localhost/coin-deals/admin.php">C<span>asin</span>O</a></div>
        </div>
        <div class="nav-links">
            <a href="#home" class="link">Home</a>
            <a href="#agents" class="link">Agents</a>
            <a href="#users" class="link">Users</a>
            <a href="#history" class="link">History</a>
        </div>
        <div class="log-details">
            <p class="admin-name"><?php echo htmlspecialchars($adminName); ?></p>
            <form method="POST">
                <input type="submit" name="logout" class="btn ad-btn" value="Logout">
            </form>
        </div>
    </header>

    <section class="admin-notice">
        <div class="notice">
            <marquee behavior="slide" direction="left" loop="5">
                📢This site is under construction.🚨 || 📢This site isunder construction.🚨 || 📢This site is under
                construction.🚨
            </marquee>
        </div>
    </section>

    <section class="balance-panel" id="home">
        <div class="balance-container">
            <div class="balance-card special-card">
                <p class="balance-title special-title">
                    Total Avail. Bal.
                </p>
                <h2 class="balance-show">
                    🪙<?php echo $u['adminAvailBal']; ?>
                </h2>
            </div>
            <div class="balance-card">
                <p class="balance-title">
                    Total Diposit Bal.
                </p>
                <h2 class="balance-show">
                    🪙<?php echo $u['adminTotalDipoBal']; ?>
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
                    👨‍✈️<?php echo $totalAgent; ?>
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
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="afname" placeholder="abc">
            <span class="error"><?php echo $nameErr; ?></span>

            <label>Last Name:</label>
            <input type="text" name="alname" placeholder="xyz">

            <label>Phone No.:</label>
            <input type="number" name="aphn" placeholder="0123456789">
            <span class="error"><?php echo $phnErr; ?></span>

            <label>Email:</label>
            <input type="email" name="aemail" placeholder="abc@example.com">
            <span class="error"><?php echo $emailErr; ?></span>

            <label for="status">Status:</label>
            <select name="astatus">
                <option value="1">Activate</option>
                <option value="0">Deactivate</option>
            </select>

            <label>Note:</label>
            <textarea name="anote" rows="4" placeholder="Note or information"></textarea>

            <label>Password:</label>
            <input type="password" name="apass" placeholder="">
            <span class="error"><?php echo $passErr; ?></span>

            <label>Confirm Password:</label>
            <input type="password" name="aconpass" placeholder="">
            <span class="error"><?php echo $passErr; ?></span>

            <span class="error"><?php echo $insertErr; ?></span>

            <input class="btn add-btn" type="submit" name="addAgent" value="Add Agent">
        </form>
    </section>

    <section class="balance-panel">
        <div class="agents-container">
            <table class="agent-list" border="1">
                <tr>
                    <th>Agent ID</th>
                    <th>Name</th>
                    <th>Phone No.</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th>Password</th>
                    <th>Total Users</th>
                    <th>Total Avil. Bal.</th>
                    <th>Action</th>
                </tr>
                <?php
                // Fetch combined data from agents and agentsacc
                $query = "SELECT a.agentID, a.aFName, a.aLName, a.aPHN, a.aEmail, a.aStatus, a.aNote, a.agConPass, 
                 acc.agTotalUsers, acc.agAvailBal 
          FROM agents a
          LEFT JOIN agentsacc acc ON a.agentID = acc.agentID"; // Assuming agentID is the common key
                
                $result = mysqli_query($conn, $query);

                // Display data
                while ($con = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$con['agentID']}</td>
                            <td>{$con['aFName']} {$con['aLName']}</td>
                            <td>{$con['aPHN']}</td>
                            <td>{$con['aEmail']}</td>
                            <td>{$con['aStatus']}</td>
                            <td>{$con['aNote']}</td>
                            <td>{$con['agConPass']}</td>
                            <td>{$con['agTotalUsers']}</td>
                            <td>{$con['agAvailBal']}</td>
                            <td>
                            <form method='POST'>
                                <input type='submit' name='delBtn' class='btn ad-btn' value='Delete'>
                                <a class='btn ad-btn' href='update.php?edit={$con['agentID']}'>Edit</a>
                            </form>
                        </td>
                    </tr>";
                }
                ?>

            </table>
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