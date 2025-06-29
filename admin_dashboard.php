<?php
session_start();
require_once 'config.php';

//  SESSION CHECK — Only logged-in Admins can access
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_register.php");
    exit();
}

$email = $_SESSION['email'];

//  Fetch admin name
$stmt = $conn->prepare("SELECT Fname, Lname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

// Handle Approve / Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = (int)$_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $stmtUpdate = $conn->prepare("UPDATE reschedule_requests SET status = 'Accepted', forwarded_to_coordinator = 1 WHERE id = ?");
    } elseif ($action === 'reject') {
        $stmtUpdate = $conn->prepare("UPDATE reschedule_requests SET status = 'Rejected' WHERE id = ?");
    } else {
        $stmtUpdate = null;
    }

    if ($stmtUpdate) {
        $stmtUpdate->bind_param("i", $request_id);
        $stmtUpdate->execute();
        $_SESSION['admin_msg'] = "Request #$request_id has been updated.";
        header("Location: admin_dashboard.php");
        exit();
    }
}

// Fetch reschedule requests not yet forwarded
$stmt2 = $conn->prepare("SELECT id, email, sub_id, lab_date, reason, medical_image, status, coordinator_status, submitted_at FROM reschedule_requests WHERE forwarded_to_coordinator = 0 ORDER BY submitted_at DESC");
$stmt2->execute();
$requests = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <section>
        <h2>Pending Reschedule Requests</h2>

        <?php if (!empty($_SESSION['admin_msg'])): ?>
            <p class="success-message"><?php
                echo htmlspecialchars($_SESSION['admin_msg']);
                unset($_SESSION['admin_msg']);
            ?></p>
        <?php endif; ?>

        <?php if ($requests->num_rows === 0): ?>
            <p>No pending requests.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Email</th>
                    <th>Subject</th>
                    <th>Lab Date</th>
                    <th>Reason</th>
                    <th>Medical File</th>
                    <th>Status</th>
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
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                        <td>
                            <form method="POST" action="admin_dashboard.php" style="display:inline-block">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="accept" class="btn accept-btn">Approve</button>
                            </form>
                            <form method="POST" action="admin_dashboard.php" style="display:inline-block">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="reject" class="btn reject-btn">Reject</button>
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
