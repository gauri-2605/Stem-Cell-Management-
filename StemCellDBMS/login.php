<?php
session_start();
include("config/db_connect.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION["user"] = $email;
            header("Location: home.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with that email!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - StemCellDBMS</title>
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
        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        .login-box h2 { margin-bottom: 30px; color: #333; }
        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background: #2980b9;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .login-box button:hover { background: #3498db; }
        .login-box .error { color: red; margin-bottom: 15px; }
        .login-box p { margin-top: 15px; color: #555; }
        .login-box p a { color: #2980b9; text-decoration: none; font-weight: bold; }
        .login-box p a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>StemCellDBMS Login</h2>
        <?php if($error != "") { echo "<div class='error'>$error</div>"; } ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don’t have an account? <a href="signup.php">Sign up</a></p>
    </div>
</body>
</html>
