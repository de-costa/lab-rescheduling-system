<?php
session_start();

$step = 1;
$role = $_POST['role'] ?? '';

if (isset($_POST['submit_role'])) {
    // User submitted first step: basic info + role
    $step = 2;
} elseif (isset($_POST['register'])) {
    // User submitted final step, process registration here
    require_once 'login_register.php';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Lab Rescheduling System</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <?php
    if (isset($_SESSION['register_error'])) {
        echo "<p class='error-message'>" . $_SESSION['register_error'] . "</p>";
        unset($_SESSION['register_error']);
    }
    if (isset($_SESSION['register_success'])) {
        echo "<p class='success-message'>" . $_SESSION['register_success'] . "</p>";
        unset($_SESSION['register_success']);
    }
    ?>

    <?php if ($step == 1): ?>
        <form method="POST" action="">
            <input type="text" name="Fname" placeholder="First Name" required>
            <input type="text" name="Lname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>

            <select name="role" required>
                <option value="">Select Role</option>
                <option value="student">Student</option>
                <option value="admin">Admin</option>
                <option value="coordinator">Coordinator</option>
                <option value="lab_instructor">Lab Instructor</option>
            </select>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="submit_role" class="btn">Next</button>
        </form>

    <?php elseif ($step == 2): ?>
        <form method="POST" action="">
            
            <input type="hidden" name="Fname" value="<?php echo htmlspecialchars($_POST['Fname']); ?>">
            <input type="hidden" name="Lname" value="<?php echo htmlspecialchars($_POST['Lname']); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
            <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
            <input type="hidden" name="password" value="<?php echo htmlspecialchars($_POST['password']); ?>">

            <h3>Role Specific Details for <?php echo ucfirst($role); ?></h3>

            <?php if ($role === 'student'): ?>
                <input type="number" name="dept_id" placeholder="Department ID" required>
                <input type="number" name="semester_id" placeholder="Semester ID" required>
                <input type="text" name="group" placeholder="Group" required>

            <?php elseif ($role === 'coordinator'): ?>
                <input type="text" name="sub_id" placeholder="Subject ID" required>

            <?php elseif ($role === 'lab_instructor'): ?>
                <input type="text" name="sub_id" placeholder="Subject ID" required>
                <input type="number" name="lab_id" placeholder="Lab ID" required>
            <?php else: ?>
                <p>No additional info needed for Admin role.</p>
            <?php endif; ?>

            <button type="submit" name="register" class="btn">Register</button>
        </form>
    <?php endif; ?>

    <p class="switch-link">Already have an account? <a href="index.php">Login here</a></p>
</div>
</body>
</html>
