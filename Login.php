<?php
session_start();

$username = trim($_POST['username']);
$password = $_POST['password'];

$con = new mysqli("localhost", "root", "", "cheetask");

if ($con->connect_error) {
    die("Failed to connect: " . $con->connect_error);
} else {
    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt_result = $stmt->get_result();

    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();
        if ($data['password'] === $password) {
            $_SESSION['id'] = $data['id'];
            if ($data['user_type'] === 'admin') {
                header("Location: AdminDashboard.php");
            } else {
                header("Location: Dashboard.php");
            }
            exit();
        } else {
            echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                let errorBox = document.createElement("div");
                errorBox.innerHTML = "Invalid Username or Password Refreshing Page...";
                errorBox.style.position = "fixed";
                errorBox.style.top = "50%";
                errorBox.style.left = "50%";
                errorBox.style.transform = "translate(-50%, -50%)";
                errorBox.style.backgroundColor = "#ffcccc";
                errorBox.style.padding = "20px";
                errorBox.style.border = "1px solid #ff0000";
                errorBox.style.borderRadius = "10px";
                errorBox.style.color = "#ff0000";
                errorBox.style.textAlign = "center";
                errorBox.style.zIndex = "1000";
                document.body.appendChild(errorBox);
    
                setTimeout(() => {
                    errorBox.remove();
                    window.location.href = "Login.html";
                }, 3000);
            });
        </script>';
        }
    } else {
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            let errorBox = document.createElement("div");
            errorBox.innerHTML = "Invalid Username or Password Refreshing Page...";
            errorBox.style.position = "fixed";
            errorBox.style.top = "50%";
            errorBox.style.left = "50%";
            errorBox.style.transform = "translate(-50%, -50%)";
            errorBox.style.backgroundColor = "#ffcccc";
            errorBox.style.padding = "20px";
            errorBox.style.border = "1px solid #ff0000";
            errorBox.style.borderRadius = "10px";
            errorBox.style.color = "#ff0000";
            errorBox.style.textAlign = "center";
            errorBox.style.zIndex = "1000";
            document.body.appendChild(errorBox);

            setTimeout(() => {
                errorBox.remove();
                window.location.href = "Login.html";
            }, 3000);
        });
    </script>';
    }
}
?>
