<?php
include 'db.php';

// Fetch messages
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Message</th>
            <th>Timestamp</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $msg): ?>
                <tr>
                    <td><?php echo $msg['id']; ?></td>
                    <td><?php echo htmlspecialchars($msg['username']); ?></td>
                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                    <td><?php echo htmlspecialchars($msg['message']); ?></td>
                    <td><?php echo $msg['created_at']; ?></td>
                    <td>
                        <!-- Delete Button -->
                        <a href="../msg/delmes.php?id=<?php echo $msg['id']; ?>" 
                           title="Delete Message" 
                           class="btn btn-outline-danger"
                           onclick="return confirm('Are you sure you want to delete this message?');">
                           <i class="fas fa-trash"></i> 
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No messages found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>