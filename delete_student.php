<?php
include('db_config.php');

if (isset($_GET['id'])) {
    // 1. Sanitize the incoming ID (handles special characters like '#')
    $u_id = mysqli_real_escape_string($conn, $_GET['id']);

    // 2. Query the files BEFORE we run the delete query
    $fetch_query = "SELECT photo, signature, qr_path FROM students WHERE unique_id = '$u_id'";
    $fetch_result = mysqli_query($conn, $fetch_query);
    $student = mysqli_fetch_assoc($fetch_result);

    if ($student) {
        // Build absolute path addresses for the physical files
        $photo_file = "uploads/photos/" . $student['photo'];
        $sig_file = "uploads/signatures/" . $student['signature'];
        $qr_file = $student['qr_path']; // Already contains 'uploads/qrcodes/...' from save script

        // 3. Execute the Database Deletion
        // Thanks to Step 1, this will instantly wipe out their attendance logs too!
        $delete_query = "DELETE FROM students WHERE unique_id = '$u_id'";

        if (mysqli_query($conn, $delete_query)) {

            // 4. database row is gone, safely clear out the space on the hard drive
            if (!empty($student['photo']) && file_exists($photo_file)) {
                unlink($photo_file);
            }
            if (!empty($student['signature']) && file_exists($sig_file)) {
                unlink($sig_file);
            }
            if (!empty($student['qr_path']) && file_exists($qr_file)) {
                unlink($qr_file);
            }

            echo "<script>
                    alert('Student record, history logs, and all files deleted cleanly!');
                    window.location='dashboard.php';
                  </script>";
        } else {
            echo "Database error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Student record not found.'); window.location='dashboard.php';</script>";
    }
} else {
    echo "No valid ID provided.";
}
?>