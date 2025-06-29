<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Lab Rescheduling System</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>

    <?php
    if (isset($_SESSION['login_error'])) {
        echo "<p class='error-message'>" . $_SESSION['login_error'] . "</p>";
        unset($_SESSION['login_error']);
    }
    ?>

    <form method="POST" action="login_register.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
            <option value="coordinator">Coordinator</option>
            <option value="lab_instructor">Lab Instructor</option>
        </select>

        <button type="submit" name="login" class="btn">Login</button>
    </form>

    <p class="switch-link">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
