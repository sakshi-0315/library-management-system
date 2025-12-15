<?php
include 'db.php';

$message = "";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT email FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        $message = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $email, $pass);

        if($stmt->execute()){
            $message = "Registration successful!";
        } else {
            $message = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Library Registration</title>

<style>
body {
    margin: 0;
    padding: 0;
    background: linear-gradient(90deg, #08203E, #557C93);
    font-family: Arial, sans-serif;
}

/* Center layout */
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    gap: 1px;
}


.container img {
    width: 545px;
    height: auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}


.form-box {
    background: white;
    padding: 35px 45px;     
    width: 430px;          
    box-shadow: 0 4px 15px rgba(0,0,0,0.35);
}

/* Inputs */
form input {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}

/* Button */
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

<h2>Library Management System - Registration</h2>

<div class="container">

   
    <div class="form-box">
        <p style="color:red;"><?php echo $message; ?></p>

        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <br>
        <a href="login.php">Already have an account? Login</a>
    </div>
 <img src="book1.jpg" alt="Library">

</div>

</body>
</html>
