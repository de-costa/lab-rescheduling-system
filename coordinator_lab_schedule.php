<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'coordinator') {
    header("Location: login_register.php");
    exit();
}

$email = $_SESSION['email'];

// Get coordinator's name
$stmt = $conn->prepare("SELECT Fname, Lname FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

// Fetch coordinator's subjects
$stmt = $conn->prepare("SELECT Sub_id FROM subject WHERE Sub_coordinator_id = (SELECT id FROM users WHERE email = ?)");
$stmt->bind_param("s", $email);
$stmt->execute();
$subResult = $stmt->get_result();

$subjects = [];
while ($row = $subResult->fetch_assoc()) {
    $subjects[] = $row['Sub_id'];
}

$subjectList = "'" . implode("','", $subjects) . "'";

$labs = [];
if (!empty($subjects)) {
    $query = "SELECT * FROM lab WHERE Sub_id IN ($subjectList) ORDER BY Lab_date ASC";
    $labs = $conn->query($query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Lab Schedule</title>
    <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
<div class="container">
    <header>
        <h1>My Lab Schedule</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></p>
        <a href="coordinator_dashboard.php" class="btn">Back to Dashboard</a>
    </header>

    <section>
        <h2>Scheduled Labs</h2>
        <?php if (!empty($labs) && $labs->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Lab ID</th>
                        <th>Subject ID</th>
                        <th>Lab Name</th>
                        <th>Lab Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $labs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Lab_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Sub_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Lab_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Lab_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No lab schedule found for your subjects.</p>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
