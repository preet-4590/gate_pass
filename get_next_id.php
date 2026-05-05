<?php
include('db_config.php');

if (isset($_GET['institution'])) {
    $inst = mysqli_real_escape_string($conn, $_GET['institution']);

    // Mapping prefixes
    $map = ['GNDEC' => '#E', 'GNDPC' => '#P', 'GNDITI' => '#I'];
    $prefix = $map[$inst] ?? '#';

    // Count existing records for this specific institution
    $query = "SELECT COUNT(*) as total FROM students WHERE institution = '$inst'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $next_id = $prefix . ($row['total'] + 1);
    echo $next_id;
}
?>