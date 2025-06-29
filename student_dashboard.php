<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login_register.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch student's full name
$stmt = $conn->prepare("SELECT Fname, Lname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch all reschedule requests by this student
$stmt2 = $conn->prepare("SELECT sub_id, lab_date, reason, medical_image, status, coordinator_status, forwarded_to_coordinator, submitted_at FROM reschedule_requests WHERE email = ? ORDER BY submitted_at DESC");
$stmt2->bind_param("s", $email);
$stmt2->execute();
$requests = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Reschedule Requests</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .submit-btn {
            background-color: #2b6cb0;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            font-weight: 600;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #2c5282;
        }
    </style>
</head>
<body>
<div class="container">
    <header class="top-bar">
        <h1>Welcome, <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <section>
        <div class="top-bar" style="margin-bottom: 20px;">
            <h2>Your Reschedule Requests</h2>
            <a href="student_request_form.php" class="submit-btn">Submit New Request</a>
        </div>

        <?php if (!empty($_SESSION['success_msg'])): ?>
            <p class="success-message"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
        <?php endif; ?>

        <?php if ($requests->num_rows === 0): ?>
            <p>You have not submitted any reschedule requests yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Subject ID</th>
                        <th>Lab Date</th>
                        <th>Reason</th>
                        <th>Medical Image</th>
                        <th>Admin Status</th>
                        <th>Forwarded to Coordinator?</th>
                        <th>Coordinator Status</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['sub_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['lab_date']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['reason'])); ?></td>
                            <td>
                                <?php if (!empty($row['medical_image'])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($row['medical_image']); ?>" target="_blank">View</a>
                                <?php else: ?>
                                    None
                                <?php endif; ?>
                            </td>
                            <td class="status <?php echo strtolower($row['status']); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </td>
                            <td><?php echo $row['forwarded_to_coordinator'] ? 'Yes' : 'No'; ?></td>
                            <td class="status <?php echo strtolower($row['coordinator_status']); ?>">
                                <?php echo htmlspecialchars($row['coordinator_status']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
