<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');

// Fetch the list of books
$books_query = $mysqli->query("SELECT * FROM books");
$books = $books_query->fetch_all(MYSQLI_ASSOC);

// Fetch the list of book requests
$requests_query = $mysqli->query("
    SELECT br.request_id, u.username, b.title, br.request_date, br.status 
    FROM book_requests br
    JOIN userss  u ON br.user_id = u.user_id
    JOIN books b ON br.book_id = b.book_id
");
$requests = $requests_query->fetch_all(MYSQLI_ASSOC);

// Fetch total users
$total_users_result = $mysqli->query("SELECT COUNT(*) AS user_count FROM users");
$total_users_data = $total_users_result->fetch_assoc();
$total_users = $total_users_data['user_count'];

// Handle the Approve/Decline actions
if (isset($_POST['action']) && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $update_query = $mysqli->query("UPDATE book_requests SET status = 'approved' WHERE request_id = $request_id");
    } elseif ($action == 'decline') {
        $update_query = $mysqli->query("UPDATE book_requests SET status = 'declined' WHERE request_id = $request_id");
    }

    // Refresh the page after action
    header('Location: admin_bookrequest.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEACE - Admin Book Requests</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="content.css">
    <link rel="shortcut icon" href="image/abc.jpg" type="image/x-icon">
</head>
<body>
    <div class="heading">
        <h1>PEACE</h1>
        <a href="logout.php"><b>Logout</b></a>
    </div>
    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_library.php">Library</a>
        <a href="admin_bookrequest.php">Book Requests</a>
        <a href="admin_usermanagement.php">User Management</a>
    </div>
    <div class="content">
        <h3>Book Requests</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Book Title</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['username']); ?></td>
                    <td><?php echo htmlspecialchars($request['title']); ?></td>
                    <td><?php echo $request['request_date']; ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td>
                        <!-- Form to approve or decline the request -->
                        <form method="POST" action="admin_bookrequest.php" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                            <button type="submit" name="action" value="approve" class="approve-btn">Approve</button>
                            <button type="submit" name="action" value="decline" class="decline-btn">Decline</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
