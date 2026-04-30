<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

/* =========================
   Fetch Dashboard Data
========================= */

// Total students
$total_query = $conn->query("SELECT COUNT(*) AS total FROM students");
$total_students = $total_query->fetch_assoc()['total'];

// Today's entries
$today_query = $conn->query("
    SELECT COUNT(*) AS today 
    FROM students 
    WHERE DATE(created_at) = CURDATE()
");
$today_entries = $today_query->fetch_assoc()['today'];

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css"> <!-- FIXED: moved here -->
</head>

<body>

    <div class="dashboard">

        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Clerk Panel</h3>
            <a href="dashboard.php">Dashboard</a>
            <a href="add_student.php">Add Student</a>
            <link rel="stylesheet" href="style.css">
            <a href="search_student.php">Search Student</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">

            <!-- Header -->
            <div class="header">
                <h2>Welcome,
                    <?php echo $_SESSION['user']; ?>
                </h2>
            </div>

            <!-- Cards -->
            <div class="cards">
                <div class="card">
                    <h3>Total Students</h3>
                    <h2>
                        <?php echo $total_students; ?>
                    </h2>
                </div>

                <div class="card">
                    <h3>Today's Entries</h3>
                    <h2>
                        <?php echo $today_entries; ?>
                    </h2>
                </div>
            </div>

        </div>
    </div>
</body>

</html>