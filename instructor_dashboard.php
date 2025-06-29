<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'lab_instructor') {
    header("Location: login_register.php");
    exit();
}

$email = $_SESSION['email'];

// Get user info
$stmt = $conn->prepare("SELECT Fname, Lname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get instructor's subject
$getSub = $conn->prepare("SELECT Sub_id FROM lab_instructor WHERE Email = ?");
$getSub->bind_param("s", $email);
$getSub->execute();
$subRow = $getSub->get_result()->fetch_assoc();
$sub_id = $subRow['Sub_id'] ?? '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = (int)$_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'schedule') {
        $new_date = $_POST['new_lab_date'] ?? null;
        if (!empty($new_date)) {
            $update = $conn->prepare("UPDATE reschedule_requests 
                                      SET coordinator_status = 'Accepted', lab_date = ? 
                                      WHERE id = ?");
            $update->bind_param("si", $new_date, $request_id);
            $update->execute();
            $_SESSION['instr_msg'] = "Scheduled lab for request #$request_id.";
        }
    } elseif ($action === 'reject') {
        $reject = $conn->prepare("UPDATE reschedule_requests SET coordinator_status = 'Rejected' WHERE id = ?");
        $reject->bind_param("i", $request_id);
        $reject->execute();
        $_SESSION['instr_msg'] = "Rejected request #$request_id.";
    } elseif ($action === 'delete') {
        $delete = $conn->prepare("DELETE FROM reschedule_requests WHERE id = ?");
        $delete->bind_param("i", $request_id);
        $delete->execute();
        $_SESSION['instr_msg'] = "Deleted request #$request_id.";
    }

    header("Location: instructor_dashboard.php");
    exit();
}

// Get requests
$stmt2 = $conn->prepare("SELECT * FROM reschedule_requests 
                         WHERE sub_id = ? AND forwarded_to_instructor = 1 
                         ORDER BY submitted_at DESC");
$stmt2->bind_param("s", $sub_id);
$stmt2->execute();
$requests = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Instructor Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <section>
        <h2>Forwarded Reschedule Requests</h2>

        <?php if (!empty($_SESSION['instr_msg'])): ?>
            <p class="success-message"><?php echo $_SESSION['instr_msg']; unset($_SESSION['instr_msg']); ?></p>
        <?php endif; ?>

        <?php if ($requests->num_rows === 0): ?>
            <p>No forwarded requests.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Subject</th>
                    <th>Lab Date</th>
                    <th>Reason</th>
                    <th>Medical</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['sub_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['lab_date']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['reason'])); ?></td>
                        <td>
                            <?php if ($row['medical_image']): ?>
                                <a href="uploads/<?php echo htmlspecialchars($row['medical_image']); ?>" target="_blank">View</a>
                            <?php else: ?>N/A<?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['coordinator_status']); ?></td>
                        <td>
                            <form method="POST" action="instructor_dashboard.php" style="display:inline-block;">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <input type="date" name="new_lab_date" required>
                                <button type="submit" name="action" value="schedule" class="btn accept-btn">Schedule</button>
                            </form>
                            <form method="POST" action="instructor_dashboard.php" style="display:inline-block;">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="reject" class="btn reject-btn">Reject</button>
                            </form>
                            <form method="POST" action="instructor_dashboard.php" style="display:inline-block;">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="delete" class="btn delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
