<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .modal {
            transition: opacity 0.3s ease-in-out;
            backdrop-filter: blur(5px);
        }
        .input-focus-effect:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        .profile-image-container {
        display: flex; /* Use flexbox to align content */
        justify-content: center; /* Center the image horizontally */
        align-items: center; /* Center the image vertically */
        margin-bottom: 10px; /* Space between the profile image and text below */
    }

    .profile-image {
        width: 130px; /* Adjust the size of the circular image */
        height: 130px; /* Keep the width and height equal to maintain the circular shape */
        border-radius: 50%; /* Makes the image circular */
        object-fit: cover; /* Ensures the image covers the circle area without distortion */
        border: 3px solid #fff; /* Optional: Adds a white border around the image */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.32); /* Optional: Adds a subtle shadow around the image */
    }
    </style>
</head>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];

$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


$username = $_SESSION['username']; // Get the logged-in username

// Query to fetch the profile image for the logged-in user
$sql = "SELECT profile_image FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

// Check if the user exists and fetch the profile image
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profile_image = $row['profile_image'];
} else {
    $profile_image = ''; // Set to empty if no profile image is found
}

mysqli_close($conn); // Close the database connection
?>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed h-full w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white">
        <div class="p-6">
            <!-- Profile Image Container Above Username -->
            <div class="profile-image-container">
                <?php
                    // Display the profile image if it exists
                    if (!empty($profile_image)) {
                        echo '<img src="uploads/' . htmlspecialchars($profile_image) . '" alt="Profile Image" class="profile-image">';
                    } else {
                        // If no profile image, show a default placeholder image
                        echo '<img src="uploads/accounticon.jpg" alt="Profile Image" class="profile-image">';
                    }
                ?>
            </div>
            <h2 class="text-2xl font-bold mb-8 text-center"><?php echo htmlspecialchars($username); ?></h2>

            <div class="space-y-4">
                <a href="adminmain.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-home mr-3"></i>Dashboard
                </a>
                <a href="account_details.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-users mr-3"></i>Accounts
                </a>
             
                <a href="Settings.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-users mr-3"></i>Settings
                </a>
                <a href="http://localhost/fitness/Employee-Attendance-Management-System/employee-attendance-management/admin/index.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all" aria-label="Go to EMS System">
                    <i class="fas fa-users mr-3"></i>EMS System
                </a>
                <a href="loggedout.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <i class="fas fa-sign-out-alt mr-3"></i>Logout
                </a>
            </div>
        </div>
    </div>
</body>

          
    <!-- Modal Form -->
    <div id="formModal" class="modal fixed inset-0 hidden z-50 flex items-center justify-center overflow-y-auto">
        <div class="bg-black bg-opacity-50 absolute inset-0"></div>
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md m-4 relative transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Create New Account</h3>
                    <button onclick="hideModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="form-container">
                    <form method="POST" action="upload_profile.php" enctype="multipart/form-data" class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">User Type</label>
                            <select name="account_type" id="accountType" 
                                    onchange="toggleDescription()"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                           transition-colors input-focus-effect" required>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-colors input-focus-effect" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-colors input-focus-effect" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                           transition-colors input-focus-effect" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Description Field - Hidden by default -->
                        <div id="descriptionField" class="space-y-2 hidden">
                            <label class="block text-sm font-medium text-gray-700">Staff Description</label>
                            <textarea name="description" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                             transition-colors input-focus-effect h-32 resize-none"
                                      placeholder="Enter your role description..."></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-colors input-focus-effect" required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                            <input type="file" name="profile_image" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                          transition-colors input-focus-effect" accept="image/*" required>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 
                                       transition-all duration-300 transform hover:-translate-y-1 
                                       flex items-center justify-center space-x-2">
                            <i class="fas fa-user-plus"></i>
                            <span>Create Account</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showModal() {
        document.getElementById('formModal').classList.remove('hidden');
        document.getElementById('formModal').classList.add('opacity-100');
    }

    function hideModal() {
        document.getElementById('formModal').classList.add('hidden');
        document.getElementById('formModal').classList.remove('opacity-100');
    }

    function toggleDescription() {
        const accountType = document.getElementById('accountType').value;
        const descriptionField = document.getElementById('descriptionField');
        const descriptionTextarea = document.querySelector('textarea[name="description"]');
        
        if (accountType === 'Staff') {
            descriptionField.classList.remove('hidden');
            descriptionTextarea.required = true;
        } else {
            descriptionField.classList.add('hidden');
            descriptionTextarea.required = false;
            descriptionTextarea.value = '';
        }
    }

    // Close modal when clicking outside
    document.getElementById('formModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });
</script>

<?php


$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle account deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $deleteMessage = "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4'>Account deleted successfully.</div>";
    } else {
        $deleteMessage = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4'>Error deleting account: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Retrieve only staff and admin accounts
$sql = "SELECT id, username, email, gender, account_type,description, created_at as member_since 
        FROM users 
        WHERE account_type IN ('Staff', 'Admin')";
$result = mysqli_query($conn, $sql);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
?>


    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .user-card {
            transition: all 0.3s ease;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .view-toggle-btn.active {
            background-color: #2563eb;
            color: white;
        }
        .form-container {
    max-height: 500px; /* Set the desired height */
    overflow-y: auto; /* Enable vertical scrolling */
    padding: 1rem;
    border: 1px solid #ddd; /* Optional: Adds a border to the container */
    border-radius: 8px; /* Optional: Adds rounded corners */
    background-color: #f9f9f9; /* Optional: Adds a background color */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: Adds a shadow */
}

    </style>
</head>

<body class="bg-gray-50">
    <!-- Main Content - Adjusted margin for sidebar -->
    <div class="ml-64 p-8">
    
       
        <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-black">Account Details</h2><br>
     

        <?php if (isset($message)) echo $message; ?>

        <!-- View Toggle and Search -->
        <div class="mb-6 flex justify-between items-center">
            <div class="flex space-x-2">
                <button onclick="toggleView('grid')" id="gridBtn" class="view-toggle-btn px-4 py-2 rounded-lg border">
                    <i class="fas fa-grid-2 mr-2"></i>Grid View
                </button>
                <button onclick="toggleView('table')" id="tableBtn" class="view-toggle-btn px-4 py-2 rounded-lg border">
                    <i class="fas fa-list mr-2"></i>Table View
                </button>
            </div>
            <div class="flex items-center space-x-4">
    <div>
        <button onclick="showModal()" 
                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 
                       transition-all duration-300 transform hover:-translate-y-1 
                       hover:shadow-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>New Account
        </button>
    </div>

    <div class="relative">
    <select onchange="sortUsers(this.value)" 
            class="appearance-none bg-white border border-gray-300 px-4 py-2 pr-8 rounded-lg 
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer
                   hover:border-blue-400 transition-colors">
        <option value="">Sort by...</option>
        <option value="nameAsc">Name (A to Z)</option>
        <option value="nameDesc">Name (Z to A)</option>
        <option value="newest">Newest First</option>
        <option value="oldest">Oldest First</option>
        <option value="roleAdmin">Admin First</option>
        <option value="roleStaff">Staff First</option>
    </select>
    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
        <i class="fas fa-chevron-down text-gray-500"></i>
    </div>
</div>
</div>
<button onclick="applySort()" 
                class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-800 
                       transition-all duration-300 flex items-center">
            <i class="fas fa-check mr-2"></i>Apply
        </button>
    <script>
    function toggleView(view) {
        const gridView = document.getElementById('gridView');
        const tableView = document.getElementById('tableView');
        const gridBtn = document.getElementById('gridBtn');
        const tableBtn = document.getElementById('tableBtn');

        if (view === 'grid') {
            gridView.classList.remove('hidden');
            tableView.classList.add('hidden');
            gridBtn.classList.add('active');
            tableBtn.classList.remove('active');
        } else {
            gridView.classList.add('hidden');
            tableView.classList.remove('hidden');
            gridBtn.classList.remove('active');
            tableBtn.classList.add('active');
        }
    }

    function sortUsers(sortType) {
    if (!sortType) return; // Exit if no sort type selected
    
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    
    // Get all cards and table rows
    const cards = Array.from(gridView.children);
    const tableBody = tableView.querySelector('tbody');
    const rows = Array.from(tableBody.getElementsByTagName('tr'));
    
    function getSortValue(element, isGrid) {
        if (isGrid) {
            const username = element.querySelector('h3').textContent.trim();
            const date = element.querySelector('div:contains("Member Since")').nextElementSibling.textContent.trim();
            const role = element.querySelector('.rounded-full').textContent.trim();
            
            return { username, date, role };
        } else {
            return {
                username: element.cells[0].textContent.trim(),
                date: element.cells[3].textContent.trim(),
                role: element.cells[2].querySelector('.rounded-full').textContent.trim()
            };
        }
    }
    
    function compareElements(a, b, isGrid) {
        const aValues = getSortValue(a, isGrid);
        const bValues = getSortValue(b, isGrid);
        
        switch (sortType) {
            case 'nameAsc':
                return aValues.username.localeCompare(bValues.username);
            case 'nameDesc':
                return bValues.username.localeCompare(aValues.username);
            case 'newest':
                return new Date(bValues.date) - new Date(aValues.date);
            case 'oldest':
                return new Date(aValues.date) - new Date(bValues.date);
            case 'roleAdmin':
                if (aValues.role === 'Admin' && bValues.role !== 'Admin') return -1;
                if (bValues.role === 'Admin' && aValues.role !== 'Admin') return 1;
                return aValues.username.localeCompare(bValues.username);
            case 'roleStaff':
                if (aValues.role === 'Staff' && bValues.role !== 'Staff') return -1;
                if (bValues.role === 'Staff' && aValues.role !== 'Staff') return 1;
                return aValues.username.localeCompare(bValues.username);
            default:
                return 0;
        }
    }
    
    // Sort grid cards
    const sortedCards = cards.sort((a, b) => compareElements(a, b, true));
    gridView.innerHTML = '';
    sortedCards.forEach(card => gridView.appendChild(card));
    
    // Sort table rows
    const sortedRows = rows.sort((a, b) => compareElements(a, b, false));
    tableBody.innerHTML = '';
    sortedRows.forEach(row => tableBody.appendChild(row));
}

// Function to apply sorting
function applySort() {
    const sortSelect = document.querySelector('select');
    const selectedValue = sortSelect.value;
    
    if (!selectedValue) {
        alert('Please select a sorting option');
        return;
    }
    
    sortUsers(selectedValue);
}

// Initialize view toggle
document.addEventListener('DOMContentLoaded', () => {
    toggleView('grid');
});

function toggleView(view) {
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const gridBtn = document.getElementById('gridBtn');
    const tableBtn = document.getElementById('tableBtn');

    if (view === 'grid') {
        gridView.classList.remove('hidden');
        tableView.classList.add('hidden');
        gridBtn.classList.add('active');
        tableBtn.classList.remove('active');
    } else {
        gridView.classList.add('hidden');
        tableView.classList.remove('hidden');
        gridBtn.classList.remove('active');
        tableBtn.classList.add('active');
    }
}
    function searchUsers() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.user-card');
        const tableRows = document.querySelectorAll('tbody tr');

        // Search in grid view
        cards.forEach(card => {
            const username = card.querySelector('h3').textContent.toLowerCase();
            const email = card.querySelector('p').textContent.toLowerCase();
            if (username.includes(input) || email.includes(input)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        // Search in table view
        tableRows.forEach(row => {
            const username = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            if (username.includes(input) || email.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this account?')) {
            window.location.href = '?delete_id=' + id;
        }
    }

    // Set grid view as default
    document.addEventListener('DOMContentLoaded', () => {
        toggleView('grid');
    });
</script>
    <div class="flex space-x-4">
        <input type="text" id="searchInput" onkeyup="searchUsers()" 
               placeholder="Search accounts..." 
               class="px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 w-64
                      hover:border-blue-400 transition-colors">
    </div>
</div>
   <!-- Grid View -->
<div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
   <?php 
   $conn = mysqli_connect("localhost", "root", "", "studentform");
   if (!$conn) {
       die("Database connection failed: " . mysqli_connect_error());
   }

   $sql = "SELECT id, username, email, gender, account_type, description, created_at as member_since, profile_image 
           FROM users WHERE account_type IN ('Staff', 'Admin')";
           
   $result = mysqli_query($conn, $sql);
   
   if (mysqli_num_rows($result) > 0) {
       while ($user = mysqli_fetch_assoc($result)) { 
           $profileImage = !empty($user['profile_image']) ? "uploads/" . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8') : "admin.png"; 
           ?>
  <div class="user-card bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Card Header -->
    <div class="p-6 <?php echo $user['account_type'] == 'Admin' ? 'bg-gradient-to-r from-purple-500 to-indigo-500' : 'bg-gradient-to-r from-cyan-600 to-blue-600'; ?>">
        <div class="flex justify-center mb-4">
            <div class="w-32 h-32 rounded-full overflow-hidden">
                <img src="<?php 
                    // Check if profile image exists, if not use default based on account type
                    if (empty($user['profile_image'])) {
                        echo 'admin.png'; // Default image for admin or staff
                    } else {
                        echo 'uploads/' . htmlspecialchars($user['profile_image'], ENT_QUOTES, 'UTF-8');
                    }
                ?>" 
                alt="<?php echo htmlspecialchars($user['username']); ?>'s profile"
                class="w-full h-full object-cover">
            </div>
       
    


                   </div>
                   <h3 class="text-xl font-bold text-white text-center"><?php echo htmlspecialchars($user['username']); ?></h3>
                   <p class="text-white text-center opacity-90"><?php echo htmlspecialchars($user['email']); ?></p>
               </div>
               <!-- Card Body -->
               <div class="p-6">
                   <div class="space-y-4">
                       <div class="flex justify-between items-center">
                           <span class="text-gray-600">Role</span>
                           <span class="px-3 py-1 rounded-full text-sm <?php echo $user['account_type'] == 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800'; ?>">
                               <?php echo ucfirst($user['account_type']); ?>
                           </span>
                       </div>
                       <div class="flex justify-between items-center">
                           <span class="text-gray-600">Gender</span>
                           <span class="text-gray-800"><?php echo htmlspecialchars($user['gender']); ?></span>
                       </div>
                       <div class="flex justify-between items-center">
                           <span class="text-gray-600">Member Since</span>
                           <span class="text-gray-800"><?php echo date('Y-m-d', strtotime($user['member_since'])); ?></span>
                       </div>
                   </div>

                   <?php if ($user['account_type'] == 'Staff' && !empty($user['description'])): ?>
                       <div class="mt-4">
                           <span class="text-gray-600">Description</span>
                           <p class="text-gray-500 text-sm mt-2"><?php echo htmlspecialchars($user['description']); ?></p>
                       </div>
                   <?php endif; ?>

                   <button onclick="confirmDelete(<?php echo $user['id']; ?>)" 
                           class="w-full mt-6 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                       <i class="fas fa-trash-alt mr-2"></i>Delete Account
                   </button>
               </div>
           </div>
       <?php }
   }
   mysqli_close($conn);
   ?>
</div>

        <!-- Table View -->
        <div id="tableView" class="hidden">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-left">Username</th>
                            <th class="px-6 py-4 bg-gradient-to-r from-blue-700 to-blue-800 text-white text-left">Email</th>
                            <th class="px-6 py-4 bg-gradient-to-r from-blue-800 to-blue-900 text-white text-left">Role</th>
                            <th class="px-6 py-4 bg-gradient-to-r from-blue-900 to-indigo-900 text-white text-left">Gender</th>
                            <th class="px-6 py-4 bg-gradient-to-r from-indigo-900 to-indigo-800 text-white text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm <?php echo $user['account_type'] == 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800'; ?>">
                                        <?php echo ucfirst($user['account_type']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($user['gender']); ?></td>
                                <td class="px-6 py-4">
                                    <button class="text-red-500 hover:text-red-700 transition-colors" 
                                            onclick="confirmDelete(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-trash-alt mr-1"></i>Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleView(view) {
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const gridBtn = document.getElementById('gridBtn');
            const tableBtn = document.getElementById('tableBtn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                gridBtn.classList.add('active');
                tableBtn.classList.remove('active');
            } else {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
                gridBtn.classList.remove('active');
                tableBtn.classList.add('active');
            }
        }

        function searchUsers() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.user-card');
            const tableRows = document.querySelectorAll('tbody tr');

            // Search in grid view
            cards.forEach(card => {
                const username = card.querySelector('h3').textContent.toLowerCase();
                const email = card.querySelector('p').textContent.toLowerCase();
                if (username.includes(input) || email.includes(input)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Search in table view
            tableRows.forEach(row => {
                const username = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                if (username.includes(input) || email.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this account?')) {
                window.location.href = '?delete_id=' + id;
            }
        }

        // Set grid view as default
        document.addEventListener('DOMContentLoaded', () => {
            toggleView('grid');
        });
        function toggleView(view) {
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const gridBtn = document.getElementById('gridBtn');
    const tableBtn = document.getElementById('tableBtn');

    if (view === 'grid') {
        gridView.classList.remove('hidden');
        tableView.classList.add('hidden');
        gridBtn.classList.add('active');
        tableBtn.classList.remove('active');
    } else {
        gridView.classList.add('hidden');
        tableView.classList.remove('hidden');
        gridBtn.classList.remove('active');
        tableBtn.classList.add('active');
    }
}


function searchUsers() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.user-card');
    const tableRows = document.querySelectorAll('tbody tr');

    // Search in grid view
    cards.forEach(card => {
        const username = card.querySelector('h3').textContent.toLowerCase();
        const email = card.querySelector('p').textContent.toLowerCase();
        if (username.includes(input) || email.includes(input)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });

    // Search in table view
    tableRows.forEach(row => {
        const username = row.cells[0].textContent.toLowerCase();
        const email = row.cells[1].textContent.toLowerCase();
        if (username.includes(input) || email.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this account?')) {
        window.location.href = '?delete_id=' + id;
    }
}

// Set grid view as default
document.addEventListener('DOMContentLoaded', () => {
    toggleView('grid');
});
    </script>
</body>
</html>