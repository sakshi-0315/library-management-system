<?php
session_start();
include 'db.php';

$message = "";

// Load saved email if Remember Me cookie exists
$saved_email = "";
if(isset($_COOKIE['remember_email'])){
    $saved_email = $_COOKIE['remember_email'];
}

if(isset($_POST['login'])){
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); // checkbox

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){
        $stmt->bind_result($id, $name, $hashed);
        $stmt->fetch();

        if(password_verify($password, $hashed)){

            // Remember me cookie (7 days)
            if($remember){
                setcookie("remember_email", $email, time() + (7 * 24 * 60 * 60), "/");
            } else {
                setcookie("remember_email", "", time() - 3600, "/");
            }

            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $name;

            header("Location: admin_dashboard.php");
            exit;
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No account found with this email!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Library Login</title>

<style>
body {
    margin: 0;
    padding: 0;
    background: linear-gradient(90deg, #08203E, #557C93);
    font-family: Arial, sans-serif;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    gap: 1px;
}

.container img {
    width: 540px;
    height: auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.form-box {
    background: white;
    padding: 35px 45px;
    width: 420px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.35);
}

form input {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}

/* Password field container */
.password-box {
    position: relative;
}

.password-box input {
    width: 100%;
}

.eye-icon {
    position: absolute;
    right: 12px;
    top: 18px;
    cursor: pointer;
    font-size: 18px;
    color: #555;
}
.remember-me {
    display: flex;
    align-items: center;
    margin-top: 5px;
    margin-bottom: 15px;
    font-size: 14px;
}

.remember-me input {
    margin-right: 8px;
    width: 16px;
    height: 16px;
}
button {
    width: 100%;
    padding: 12px;
    background: #08203E;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #0d3f73;
}

h2 {
    color: white;
    text-align: center;
    margin-top: 20px;
    font-size: 28px;
}
</style>

</head>
<body>

<h2>Library Management System - Login</h2>

<div class="container">

    <img src="book1.jpg" alt="Library">

    <div class="form-box">
        <p style="color:red;"><?php echo $message; ?></p>

        <form method="POST">

            <input type="email" 
                   name="email" 
                   placeholder="Email Address" 
                   value="<?php echo $saved_email; ?>" 
                   required>

            <!-- Password With Eye Icon -->
            <div class="password-box">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="eye-icon" onclick="togglePassword()">👁️</span>
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>
    

            <button type="submit" name="login">Login</button>
        </form>

        <br>
        <a href="registration.php">Don't have an account? Register</a>
    </div>

</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
