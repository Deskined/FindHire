<?php
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

// Fetch applications
$searchQuery = '';
if (isset($_GET['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM job_applications WHERE 
        id LIKE :search OR 
        firstname LIKE :search OR 
        lastname LIKE :search OR 
        email LIKE :search OR 
        username LIKE :search OR 
        job_title LIKE :search 
        ORDER BY id DESC");
    $stmt->execute(['search' => '%' . $searchQuery . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM job_applications ORDER BY id DESC");
}
$applications = $stmt->fetchAll();
?>


<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Job Title</th>
            <th>Cover Letter</th>
            <th>Resume</th>
            <th>Actions</th>
            <th>Status</th>
            <th>Job Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($applications) > 0): ?>
            <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?php echo $app['id']; ?></td>
                    <td><?php echo htmlspecialchars($app['username']); ?></td>
                    <td><?php echo htmlspecialchars($app['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($app['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($app['email']); ?></td>
                    <td><?php echo htmlspecialchars($app['phone']); ?></td>
                    <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($app['cover_letter']); ?></td>
                    <td>
                        <div class="btn-group mt-2" role="group">
                            <?php if (!empty($app['resume'])): ?>
                                <a href="../uploads/<?php echo htmlspecialchars($app['resume']); ?>" title="View Resume" target="_blank" class="btn btn-outline-primary me-2"><i class="fa-regular fa-eye"></i></a>
                                <a href="../uploads/<?php echo htmlspecialchars($app['resume']); ?>" title="Download Resume" download class="btn btn-outline-primary"><i class="fa-solid fa-download"></i></a>
                            <?php else: ?>
                                <span class="text-muted">No Resume</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group mt-2" role="group">
                            <a href="../appli/delete.php?id=<?php echo $app['id']; ?>" title="Delete Application" class="btn btn-outline-danger"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group mt-2" role="group">
                            <!-- Accept Button -->
                            <a href="../appli/status.php?action=accept&application_id=<?php echo $app['id']; ?>" 
                            title="Accept Application" 
                            class="btn btn-outline-success me-2">
                            <i class="fa-solid fa-check-to-slot"></i>
                            </a>

                            <!-- Reject Button -->
                            <a href="../appli/status.php?action=reject&application_id=<?php echo $app['id']; ?>" 
                            title="Reject Application" 
                            class="btn btn-outline-danger">
                            <i class="fa-solid fa-ban"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        <?php
                        // Check the status and display the appropriate icon
                        if ($app['status'] == 'Accepted') {
                            echo '<i class="fa-solid fa-check-circle" style="color: green;" title="Accepted"></i>';
                        } elseif ($app['status'] == 'Rejected') {
                            echo '<i class="fa-solid fa-circle-xmark" style="color: red;" title="Rejected"></i>';
                        } else {
                            echo '<i class="fa-solid fa-clock" style="color: orange;" title="Pending"></i>';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="text-center">No results found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
