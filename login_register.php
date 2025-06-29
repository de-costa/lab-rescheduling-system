<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            switch ($user['role']) {
                case 'student':
                    header("Location: student_dashboard.php"); exit();
                case 'admin':
                    header("Location: admin_dashboard.php"); exit();
                case 'coordinator':
                    header("Location: coordinator_dashboard.php"); exit();
                case 'lab_instructor':
                    header("Location: instructor_dashboard.php"); exit();
                default:
                    $_SESSION['login_error'] = "Unknown role.";
                    header("Location: login_register.php"); exit();
            }
        } else {
            $_SESSION['login_error'] = "Invalid credentials.";
            header("Location: login_register.php"); exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid credentials.";
        header("Location: login_register.php"); exit();
    }
}


if (isset($_POST['register'])) {
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $Dept_id = $_POST['dept_id'] ?? null;
    $Semester_id = $_POST['semester_id'] ?? null;
    $Group = $_POST['group'] ?? null;
    $Sub_id = $_POST['sub_id'] ?? null;
    $Lab_id = $_POST['lab_id'] ?? null;

    // Check for duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $_SESSION['register_error'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // Validate foreign keys
    if ($role === 'student') {
        $deptCheck = $conn->prepare("SELECT Dept_id FROM department WHERE Dept_id = ?");
        $deptCheck->bind_param("i", $Dept_id);
        $deptCheck->execute();
        if ($deptCheck->get_result()->num_rows === 0) {
            $_SESSION['register_error'] = "Invalid Department ID.";
            header("Location: register.php"); exit();
        }
    }

    if ($role === 'coordinator' || $role === 'lab_instructor') {
        $subCheck = $conn->prepare("SELECT Sub_id FROM subject WHERE Sub_id = ?");
        $subCheck->bind_param("s", $Sub_id);
        $subCheck->execute();
        if ($subCheck->get_result()->num_rows === 0) {
            $_SESSION['register_error'] = "Invalid Subject ID.";
            header("Location: register.php"); exit();
        }
    }

    if ($role === 'lab_instructor' && $Lab_id !== null) {
        $labCheck = $conn->prepare("SELECT Lab_id FROM lab WHERE Lab_id = ?");
        $labCheck->bind_param("i", $Lab_id);
        $labCheck->execute();
        if ($labCheck->get_result()->num_rows === 0) {
            $_SESSION['register_error'] = "Invalid Lab ID.";
            header("Location: register.php"); exit();
        }
    }

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (Fname, Lname, email, role, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $Fname, $Lname, $email, $role, $password);
    if (!$stmt->execute()) {
        $_SESSION['register_error'] = "Failed to register user.";
        header("Location: register.php");
        exit();
    }

    // Insert into role-specific table
    switch ($role) {
        case 'student':
            $stmt2 = $conn->prepare("INSERT INTO student (Fname, Lname, email, Dept_id, Semester_id, `Group`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssiss", $Fname, $Lname, $email, $Dept_id, $Semester_id, $Group);
            break;

        case 'admin':
            $stmt2 = $conn->prepare("INSERT INTO admin (email) VALUES (?)");
            $stmt2->bind_param("s", $email);
            break;

        case 'coordinator':
            $stmt2 = $conn->prepare("INSERT INTO coordinator (Coordinator_name, Email, Sub_id) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $Fname, $email, $Sub_id);
            break;

        case 'lab_instructor':
            $stmt2 = $conn->prepare("INSERT INTO lab_instructor (Instructor_name, Email, Sub_id, Lab_id) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("sssi", $Fname, $email, $Sub_id, $Lab_id);
            break;
    }

    if (isset($stmt2) && !$stmt2->execute()) {
        $_SESSION['register_error'] = "Failed to insert into $role table: " . $stmt2->error;
        header("Location: register.php");
        exit();
    }

    $_SESSION['register_success'] = "Registration successful. Please log in.";
    header("Location: login_register.php");
    exit();
}
?>
