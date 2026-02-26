<?php
// 1. Database Configuration
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "project-1";

// Connection banana
$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Form Data Process karna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Data ko sanitize karna (Security)
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    // Check agar user ya email pehle se hai
    $check_query = "SELECT * FROM users WHERE username='$user' OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $check_query);
    $user_exists = mysqli_fetch_assoc($result);

    if ($user_exists) {
        echo "<script>
                alert('Username or Email already exists!');
                window.location.href='index.html'; 
              </script>";
    } else {
        // Password ko hash (encrypt) karna
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        // Database mein insert karna
        $sql = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Registration Successful! Please Login.');
                    window.location.href='home.html';
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>