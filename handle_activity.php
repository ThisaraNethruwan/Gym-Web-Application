<?php
session_start();
include 'admin_sidebar.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username']; 
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "studentform");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


// Handle delete request
if (isset($_POST['delete_activity'])) {
    $activity_id = $_POST['activity_id'];
    
    // Get media path before deleting
    $sql = "SELECT media_path FROM activities WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $activity_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (file_exists($row['media_path'])) {
            unlink($row['media_path']);
        }
    }
    
    // Delete from database
    $sql = "DELETE FROM activities WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $activity_id);
    
    if ($stmt->execute()) {
        $success_message = "Activity deleted successfully!";
    } else {
        $error_message = "Error deleting activity";
    }
}

// Handle edit/update request
if (isset($_POST['update_activity'])) {
    $activity_id = $_POST['activity_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $existing_media = $_POST['existing_media'];
    
    $media_type = $existing_media_type = $_POST['existing_media_type'];
    $media_path = $existing_media;
    
    // Handle new file upload if provided
    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $allowed_image = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_video = ['mp4', 'webm', 'avi'];
        
        $file_extension = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
        $upload_path = 'uploads/activities/';
        
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $upload_path . $file_name;
        
        if (in_array($file_extension, $allowed_image)) {
            $media_type = 'image';
        } elseif (in_array($file_extension, $allowed_video)) {
            $media_type = 'video';
        }
        
        if (move_uploaded_file($_FILES['media']['tmp_name'], $target_file)) {
            // Delete old file if exists
            if (file_exists($existing_media)) {
                unlink($existing_media);
            }
            $media_path = $target_file;
        }
    }
    
    // Update database
    $sql = "UPDATE activities SET title = ?, description = ?, media_type = ?, media_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $media_type, $media_path, $activity_id);
    
    if ($stmt->execute()) {
        $success_message = "Activity updated successfully!";
    } else {
        $error_message = "Error updating activity: " . $conn->error;
    }
}

// Handle new activity submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_activity'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $media_type = '';
    $media_path = '';
    
    if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
        $allowed_image = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_video = ['mp4', 'webm', 'avi'];
        
        $file_extension = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
        $upload_path = 'uploads/activities/';
        
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $upload_path . $file_name;
        
        if (in_array($file_extension, $allowed_image)) {
            $media_type = 'image';
        } elseif (in_array($file_extension, $allowed_video)) {
            $media_type = 'video';
        }
        
        if (move_uploaded_file($_FILES['media']['tmp_name'], $target_file)) {
            $media_path = $target_file;
        }
    }
    
    $sql = "INSERT INTO activities (title, description, media_type, media_path, created_at) 
            VALUES (?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $media_type, $media_path);
    
    if ($stmt->execute()) {
        $success_message = "Activity added successfully!";
    } else {
        $error_message = "Error adding activity: " . $conn->error;
    }
}

// Fetch all activities
$activities = [];
$sql = "SELECT * FROM activities ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Activities Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Make sure you include Tailwind CSS in your project -->
<!-- Overlay prevention layer -->
<div class="fixed top-0 right-0 w-32 h-20  z-[9999]"></div>

<!-- Profile Header -->
<div class="fixed top-4 right-4 z-[10000]">
    <div class="flex items-center gap-3 bg-white rounded-lg shadow-md p-2 hover:shadow-lg transition-shadow duration-300">
        <div class="relative">
            <?php
            if (!empty($profile_image)) {
                echo '<img src="uploads/' . htmlspecialchars($profile_image) . '" 
                    alt="Profile Image" 
                    class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">';
            } else {
                echo '<img src="uploads/default-profile.png" 
                    alt="Profile Image" 
                    class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">';
            }
            ?>
            <!-- Online status indicator -->
            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full"></div>
        </div>
        <div class="pr-2">
            <p class="text-1sm text-gray-800"><?php echo htmlspecialchars($username); ?></p>
            <p class="text-xs text-blue-700">Online</p>
        </div>
    </div>
</div>
      

            </head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Add Activity Form -->
        <section id="add-activity" class="ml-[486px] mr-64 mt-20">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8 transform hover:scale-[1.01] transition-transform duration-300">
            <h2 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-4">
                <i class="fas fa-plus-circle text-indigo-600 mr-2"></i>Add New Activity
            </h2>
            
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded animate-fade-in">
                        <i class="fas fa-check-circle mr-2"></i><?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Activity Title</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full h-10 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200"></textarea>
                </div>
                
                <div>
                    <label for="media" class="block text-sm font-medium text-gray-700">Upload Image/Video</label>
                    <input type="file" name="media" id="media" accept="image/*,video/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors duration-200">
                    <p class="mt-1 text-sm text-gray-500">Supported formats: JPG, JPEG, PNG, GIF, MP4, WEBM, AVI</p>
                </div>
                
                <input type="hidden" name="add_activity" value="1">
                
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i>Add Activity
                </button>
            </form>
        </div>
        
        <!-- Activities Grid -->
        <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-black">Your Activities</h2><br>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            
            <?php foreach ($activities as $activity): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-[1.02] transition-transform duration-300">
                <div class="relative">
                    <?php if ($activity['media_type'] == 'image'): ?>
                        <img src="<?php echo $activity['media_path']; ?>" alt="<?php echo $activity['title']; ?>" 
                             class="w-full h-56 object-cover">
                    <?php elseif ($activity['media_type'] == 'video'): ?>
                        <video controls class="w-full h-56 object-cover">
                            <source src="<?php echo $activity['media_path']; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                    
                    <!-- Action buttons overlay -->
                    <div class="absolute top-4 right-4 flex space-x-2">
                        <!-- Edit Button - Opens Modal -->
                        <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($activity)); ?>)" 
                                class="bg-white p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-edit text-indigo-600"></i>
                        </button>
                        
                        <!-- Delete Button -->
                        <form action="" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this activity?');">
                            <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                            <input type="hidden" name="delete_activity" value="1">
                            <button type="submit" class="bg-white p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-trash-alt text-red-600"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $activity['title']; ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo $activity['description']; ?></p>
                    <div class="text-sm text-gray-500 flex items-center">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <?php echo date('M d, Y', strtotime($activity['created_at'])); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl w-full mx-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                <i class="fas fa-edit text-indigo-600 mr-2"></i>Edit Activity
            </h2>
            
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="activity_id" id="edit_activity_id">
                <input type="hidden" name="existing_media" id="edit_existing_media">
                <input type="hidden" name="existing_media_type" id="edit_existing_media_type">
                <input type="hidden" name="update_activity" value="1">
                
                <div>
                    <label for="edit_title" class="block text-sm font-medium text-gray-700">Activity Title</label>
                    <input type="text" name="title" id="edit_title" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="edit_description" rows="4" required
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                
                <div>
                    <label for="edit_media" class="block text-sm font-medium text-gray-700">Upload New Image/Video (Optional)</label>
                    <input type="file" name="media" id="edit_media" accept="image/*,video/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                           <div class="mt-2 text-sm text-gray-500">Current media will be kept if no new file is uploaded</div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to open edit modal
        function openEditModal(activity) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
            
            // Populate form fields
            document.getElementById('edit_activity_id').value = activity.id;
            document.getElementById('edit_title').value = activity.title;
            document.getElementById('edit_description').value = activity.description;
            document.getElementById('edit_existing_media').value = activity.media_path;
            document.getElementById('edit_existing_media_type').value = activity.media_type;
            
            // Add scroll lock to body
            document.body.style.overflow = 'hidden';
        }

        // Function to close edit modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
            
            // Remove scroll lock from body
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeEditModal();
            }
        });

        // Add keyboard support for closing modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
        });

        // Preview image/video before upload
        document.getElementById('media').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You could add preview functionality here if desired
                    console.log('File selected:', file.name);
                }
                reader.readAsDataURL(file);
            }
        });

        // Add smooth fade-out for success/error messages
        const messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
        messages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s ease-out';
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 500);
            }, 3000);
        });
    </script>
</body>

 
</html>
