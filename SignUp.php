<?php
$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];

if (empty($username) || empty($email) || empty($password)) {
    die("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

$conn = new mysqli('localhost', 'root', '', 'cheetask');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            let errorBox = document.createElement("div");
            errorBox.innerHTML = "Username or Email already exists! Refreshing Page...";
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
                window.location.href = "SignUp.html";
            }, 3000);
        });
    </script>';
    exit();
}

$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);
$stmt->execute();
header("Location: Login.html");
exit();

$stmt->close();
$conn->close();
?>
