<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login/login.php');
    exit;
}

// Fetch job postings
$stmt = $pdo->query("SELECT * FROM job_postings");
$job_postings = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    include 'db.php';

    // Get logged-in user's username
    $username = $_SESSION['username'];  // Assuming the username is stored in the session as user_id

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cover_letter = $_POST['cover_letter'];
    $job_title = $_POST['job_title'];

    // Handle file upload for resume
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['resume']['tmp_name'];
        $fileName = $_FILES['resume']['name'];
        $fileSize = $_FILES['resume']['size'];
        $fileType = $_FILES['resume']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadFileDir = 'uploads/';
            $destPath = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Insert application including the username
                $stmt = $pdo->prepare("INSERT INTO job_applications (firstname, lastname, email, phone, cover_letter, job_title, resume, username) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$firstname, $lastname, $email, $phone, $cover_letter, $job_title, $fileName, $username]);
            } else {
                $error_message = "There was an error moving the uploaded file.";
            }
        } else {
            $error_message = "Invalid file type. Only PDF files are allowed.";
        }
    } else {
        $error_message = "Error in file upload.";
    }

    header('Location: /appli/ty.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $message = $_POST['message'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("INSERT INTO messages (username, email, message) VALUES (:username, :email, :message)");
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'message' => $message,
    ]);

    header('Location: index.php');
    exit;
}

$country_codes = [
    "+93" => "Afghanistan",
    "+355" => "Albania",
    "+213" => "Algeria",
    "+61" => "Australia",
    "+1" => "Canada",
    "+86" => "China",
    "+91" => "India",
    "+44" => "United Kingdom",
    "+1" => "United States",
    "+97" => "Saudi Arabia",
    "+880" => "Bangladesh",
    "+975" => "Bhutan",
    "+60" => "Brunei",
    "+975" => "Bhutan",
    "+91" => "India",
    "+62" => "Indonesia",
    "+98" => "Iran",
    "+964" => "Iraq",
    "+81" => "Japan",
    "+962" => "Jordan",
    "+855" => "Cambodia",
    "+254" => "Kenya",
    "+961" => "Lebanon",
    "+965" => "Kuwait",
    "+996" => "Kyrgyzstan",
    "+84" => "Vietnam",
    "+856" => "Laos",
    "+965" => "Kuwait",
    "+971" => "United Arab Emirates",
    "+976" => "Mongolia",
    "+977" => "Nepal",
    "+92" => "Pakistan",
    "+63" => "Philippines",
    "+974" => "Qatar",
    "+7" => "Kazakhstan",
    "+966" => "Saudi Arabia",
    "+82" => "South Korea",
    "+94" => "Sri Lanka",
    "+66" => "Thailand",
    "+256" => "Uganda",
    "+852" => "Hong Kong",
    "+886" => "Taiwan",
    "+90" => "Turkey",
    "+967" => "Yemen"
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>FindHire | Home</title>
</head>
<body class="bg-gradient-to-br from-blue-500 via-red-500 to-green-500 min-h-screen text-white">
<nav class="bg-gradient-to-r from-gray-800 to-gray-900 shadow-lg">
    <div class="container mx-auto p-4 flex justify-between items-center">
        <a href="#" class="text-2xl font-bold">𝙵𝚒𝚗𝚍𝙷𝚒𝚛𝚎</a>
        <ul class="flex space-x-4">
            <li><a href="viewapp.php" class="hover:text-gray-300">View Applications</a></li>
            <li><a href="#" class="hover:text-gray-300">Support</a></li>
            <li class="relative group">
                <a href="#" class="hover:text-gray-300">
                    <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
                <ul class="absolute bg-gray-800 mt-2 rounded-lg hidden group-hover:block shadow-md">
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-700">Profile</a></li>
                    <li><a href="/login/logout.php" class="block px-4 py-2 hover:bg-gray-700">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container mx-auto mt-10 p-6 bg-white bg-opacity-20 backdrop-blur-md rounded-lg shadow-lg">
    <table class="w-full text-left text-gray-900 bg-white rounded-lg">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">Job Title</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Location</th>
                <th class="px-4 py-2">Salary</th>
                <th class="px-4 py-2">Last Updated</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($job_postings as $job): ?>
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($job['job_title']); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($job['job_description']); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($job['location']); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($job['salary']); ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($job['last_updated']); ?></td>
                    <td class="border px-4 py-2">
                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600" 
                                data-bs-toggle="modal" 
                                data-bs-target="#applicationModal" 
                                onclick="setJobTitle('<?php echo htmlspecialchars($job['job_title']); ?>')">Apply</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="applicationModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Apply for Job</h2>
        <form action="apply.php" method="POST">
            <input type="hidden" name="job_title" id="job_title">
            <label class="block text-gray-700">Full Name</label>
            <input type="text" name="full_name" class="w-full p-2 border rounded mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" class="w-full p-2 border rounded mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
        </form>
    </div>
</div>

<script>
    function setJobTitle(jobTitle) {
        document.getElementById('job_title').value = jobTitle;
        document.getElementById('applicationModal').classList.remove('hidden');
    }
</script>
</body>
</html>
