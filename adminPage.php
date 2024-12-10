<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: adminLogin.php');
    exit;
}

// Fetch job postings
$stmt = $pdo->query("SELECT * FROM job_postings");
$job_postings = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate form data
        if (
            empty($_POST['job_title']) || 
            empty($_POST['job_description']) || 
            empty($_POST['location']) || 
            empty($_POST['salary'])
        ) {
            throw new Exception("All fields are required.");
        }

        $job_title = $_POST['job_title'];
        $job_description = $_POST['job_description'];
        $location = $_POST['location'];
        $salary = $_POST['salary'];
        $image_path = null; // Default image path

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $imageName = basename($_FILES['image']['name']);
            $imageTmpName = $_FILES['image']['tmp_name'];
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . $imageName;

            // Move the uploaded file
            if (move_uploaded_file($imageTmpName, $imagePath)) {
                $image_path = '/uploads/' . $imageName; // Save relative path for database
            } else {
                throw new Exception("Failed to upload image.");
            }
        }

        // Insert new job posting into the database
        $stmt = $pdo->prepare("
            INSERT INTO job_postings (job_title, job_description, location, salary, image_path, last_updated) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$job_title, $job_description, $location, $salary, $image_path]);

        // Redirect to the admin page after successful submission
        header('Location: adminPage.php');
        exit;
    } catch (Exception $e) {
        // Handle errors and display feedback
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/logorss.png" type="image/x-icon">
    <title>FindHire | Manage Jobs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/modal.css">
</head>
<body class="bg-gradient-to-r from-red via-blue-500 to-purple-600">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">𝙵𝚒𝚗𝚍𝙷𝚒𝚛𝚎</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#msgModal">
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#addJobModal">
                        Add Job
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#viewApplicationsModal">
                        Applicants
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminLogout.php">
                     Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="input-group mb-4">
                <input type="text" id="search" class="form-control" placeholder="Search for jobs..." aria-label="Search for jobs">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($job_postings as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($job['job_description']); ?></td>
                            <td><?php echo htmlspecialchars($job['location']); ?></td>
                            <td><?php echo htmlspecialchars($job['salary']); ?></td>
                            <td><?php echo htmlspecialchars($job['last_updated']); ?></td>
                            <td>
                                <a href="../edit.php?id=<?php echo $job['job_id']; ?>" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Edit Job">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="../delete.php?id=<?php echo $job['job_id']; ?>" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete Job" onclick="setDeleteLink('<?php echo $job['job_id']; ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Deleting Job -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="etstu">Are you sure you want to delete this job?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteLink" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!--User Messages-->
<div class="modal fade" id="msgModal" tabindex="-1" aria-labelledby="msgModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="msgModalLabel">Messages</h5>
            </div> 
            <div class="modal-body custom-modal-style">
                <?php include 'viewmes.php'; ?>
            </div>
            <div class="modal-footer">
                <p>&copy; <?php echo date("Y"); ?> FindHire® Global Inc.</p>
            </div>
        </div>
    </div>
</div>

<!--Apllication Job Users-->
<div class="modal fade" id="viewApplicationsModal" tabindex="-1" aria-labelledby="viewApplicationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewApplicationsModalLabel">Job Applicants</h5>
            </div> 
            <div class="modal-body custom-modal-style">
                <?php include 'view.php'; ?>
            </div>
            <div class="modal-footer">
                <p>&copy; <?php echo date("Y"); ?> FindHire® Global Inc.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding Job -->
<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addJobModalLabel">Add Job Posting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action=" " enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" class="form-control" id="job_title" name="job_title" required>
                    </div>
                    <div class="form-group">
                        <label for="job_description">Job Description</label>
                        <textarea class="form-control" id="job_description" name="job_description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <input type="text" class="form-control" id="salary" name="salary" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-rounded">Save</button>
                        <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle opening the modal and pre-filling the form via AJAX
    document.querySelectorAll('.edit-job').forEach(function(button) {
        button.addEventListener('click', function() {
            const jobId = this.dataset.jobId;
            fetch('getJobDetails.php?id=' + jobId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('job_id').value = data.job_id;
                    document.getElementById('job_title').value = data.job_title;
                    document.getElementById('job_description').value = data.job_description;
                    document.getElementById('location').value = data.location;
                    document.getElementById('salary').value = data.salary;
                    if (data.image_path) {
                        document.getElementById('current_image').src = data.image_path;
                    } else {
                        document.getElementById('current_image').style.display = 'none';
                    }
                });
        });
    });
</script>

<script>
    document.getElementById('search').addEventListener('input', function() {
        const searchQuery = this.value.toLowerCase();
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(searchQuery)) {
                card.parentElement.style.display = '';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
</script>

<script>
    function setDeleteLink(jobId) {
        var deleteLink = document.getElementById('deleteLink');
        deleteLink.href = '../delete.php?id=' + jobId; // Set the href of the delete button
    }
</script>

<script>
  // Initialize tooltips for elements with data-bs-toggle="tooltip"
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  })
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>