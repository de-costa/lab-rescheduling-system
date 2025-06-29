<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'coordinator') {
    header("Location: login_register.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch coordinator name
$stmt = $conn->prepare("SELECT Fname, Lname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

// Handle accept/reject coordinator decisions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = (int)$_POST['request_id'];
    $action = $_POST['action']; // 'accept' or 'reject'

    if ($action === 'accept') {
        // Accept and forward to instructor
        $stmtUpdate = $conn->prepare("UPDATE reschedule_requests SET coordinator_status = 'Accepted', forwarded_to_instructor = 1 WHERE id = ?");
    } elseif ($action === 'reject') {
        $stmtUpdate = $conn->prepare("UPDATE reschedule_requests SET coordinator_status = 'Rejected' WHERE id = ?");
    } else {
        $stmtUpdate = null;
    }

    if ($stmtUpdate) {
        $stmtUpdate->bind_param("i", $request_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
        $_SESSION['coord_msg'] = "Request #$request_id updated successfully.";
        header("Location: coordinator_dashboard.php");
        exit();
    }
}

// Fetch pending requests forwarded to coordinator
$stmt2 = $conn->prepare("SELECT id, email, sub_id, lab_date, reason, medical_image, status, coordinator_status, submitted_at FROM reschedule_requests WHERE forwarded_to_coordinator = 1 AND coordinator_status = 'Pending' ORDER BY submitted_at DESC");
$stmt2->execute();
$requests = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Coordinator Dashboard - Reschedule Requests</title>
    <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
<div class="container">
    <header>
        <h1>Coordinator Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></p>
        <div>
            
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <section>
        <h2>Reschedule Requests Pending Your Review</h2>

        <?php if (!empty($_SESSION['coord_msg'])): ?>
            <p class="success-message"><?php
                echo htmlspecialchars($_SESSION['coord_msg']);
                unset($_SESSION['coord_msg']);
            ?></p>
        <?php endif; ?>

        <?php if ($requests->num_rows === 0): ?>
            <p>No requests to review at the moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Email</th>
                        <th>Subject ID</th>
                        <th>Lab Date</th>
                        <th>Reason</th>
                        <th>Medical Image</th>
                        <th>Admin Status</th>
                        <th>Submitted At</th>
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
                                <?php if (!empty($row['medical_image'])): ?>
                                    <a href="uploads/<?php echo htmlspecialchars($row['medical_image']); ?>" target="_blank">View</a>
                                <?php else: ?>
                                    None
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                            <td>
                                <form method="POST" style="display:inline-block">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>" />
                                    <button type="submit" name="action" value="accept" class="btn accept-btn" onclick="return confirm('Accept this request and forward to instructor?');">Accept</button>
                                </form>
                                <form method="POST" style="display:inline-block">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>" />
                                    <button type="submit" name="action" value="reject" class="btn reject-btn" onclick="return confirm('Reject this request?');">Reject</button>
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
