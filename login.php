<?php
// 1. Session start karein taaki user logged-in rahe
session_start();

// 2. Database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "project-1";

// Connection create karein
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

// Connection check karein
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 3. Check karein ki form submit hua hai ya nahi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Form se data lena aur sanitize karna
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // Database mein user ko search karna
    $sql = "SELECT * FROM users WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // 4. Password verify karna (Jo registration ke waqt hash kiya gaya tha)
        if (password_verify($pass, $row['password'])) {
            
            // Login success: Session variables set karein
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            // Redirect to dashboard
            echo "<script>
                    alert('Login Successful! Welcome back.');
                    window.location.href='dashboard.php';
                  </script>";
        } else {
            // Password galat hai
            echo "<script>
                    alert('Invalid Password!');
                    window.location.href='home.html';
                  </script>";
        }
    } else {
        // User nahi mila
        echo "<script>
                alert('No user found with this username!');
                window.location.href='home.html';
              </script>";
    }
}

mysqli_close($conn);
?>