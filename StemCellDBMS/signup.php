<?php
session_start();
include("config/db_connect.php");

$error = ""; // To store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0){
        $error = "Email already registered! Try login.";
    } else {
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION["user"] = $email;
            header("Location: home.php");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - StemCellDBMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        .signup-box h2 {
            margin-bottom: 30px;
            color: #333;
        }
        .signup-box input[type="email"],
        .signup-box input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .signup-box button {
            width: 100%;
            padding: 12px;
            background: #2980b9;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .signup-box button:hover {
            background: #3498db;
        }
        .signup-box .error {
            color: red;
            margin-bottom: 15px;
        }
        .signup-box p {
            margin-top: 15px;
            color: #555;
        }
        .signup-box p a {
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
        }
        .signup-box p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-box">
        <h2>StemCellDBMS Signup</h2>
        <?php if($error != "") { echo "<div class='error'>$error</div>"; } ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
