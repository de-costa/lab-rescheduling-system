<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login_register.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch subjects for dropdown
$subjectResult = $conn->query("SELECT Sub_id, Sub_name FROM subject ORDER BY Sub_name ASC");

// Initialize variables for form values and messages
$error = '';
$success = '';
$sub_id = '';
$lab_date = '';
$reason = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sub_id = $_POST['sub_id'] ?? '';
    $lab_date = $_POST['lab_date'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    // Validate required fields
    if (empty($sub_id) || empty($lab_date) || empty($reason)) {
        $error = "Please fill in all required fields.";
    } else {
        $medical_image = '';

        // Handle medical image upload if exists
        if (isset($_FILES['medical_image']) && $_FILES['medical_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $tmpName = $_FILES['medical_image']['tmp_name'];
            $fileName = time() . '_' . basename($_FILES['medical_image']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Optional: Validate file type and size here before moving

            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $medical_image = $fileName;
            } else {
                $error = "Failed to upload medical image.";
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO reschedule_requests (email, sub_id, lab_date, reason, medical_image, status, coordinator_status, forwarded_to_coordinator) VALUES (?, ?, ?, ?, ?, 'Pending', 'Pending', 0)");
            $stmt->bind_param("sssss", $email, $sub_id, $lab_date, $reason, $medical_image);

            if ($stmt->execute()) {
                $success = "Reschedule request sent to Admin for review.";
                // Clear form values after success
                $sub_id = '';
                $lab_date = '';
                $reason = '';
            } else {
                $error = "Failed to send request: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Submit Reschedule Request</title>
<link rel="stylesheet" href="student_request_form.css" />
</head>
<body>
<div class="container">
    <header>
        <h1>Submit Reschedule Request</h1>
        <a href="student_dashboard.php" class="btn">Back to Dashboard</a>
    </header>

    <?php if ($error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="sub_id">Subject:</label><br />
        <select name="sub_id" id="sub_id" required>
            <option value="">-- Select Subject --</option>
            <?php while ($subject = $subjectResult->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($subject['Sub_id']); ?>" <?php if ($sub_id === $subject['Sub_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($subject['Sub_name']); ?>
                </option>
            <?php endwhile; ?>
        </select><br /><br />

        <label for="lab_date">Lab Date:</label><br />
        <input type="date" name="lab_date" id="lab_date" required value="<?php echo htmlspecialchars($lab_date); ?>" /><br /><br />

        <label for="reason">Reason:</label><br />
        <textarea name="reason" id="reason" rows="4" required><?php echo htmlspecialchars($reason); ?></textarea><br /><br />

        <label for="medical_image">Medical Certificate (optional):</label><br />
        <input type="file" name="medical_image" id="medical_image" accept="image/*,application/pdf" /><br /><br />

        <button type="submit">Submit Request</button>
    </form>
</div>
</body>
</html>
