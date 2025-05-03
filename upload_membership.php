<?php
session_start();
include 'admin_sidebar.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];

include 'db_connect.php';

// Fetch membership plans
$sql = "SELECT * FROM mem_plan";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error retrieving membership plans: " . mysqli_error($conn));
}

// Handle editing the plan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Ensure `key_points` is processed correctly
    $key_points = isset($_POST['key_points']) ? $_POST['key_points'] : '';
    if (is_array($key_points)) {
        $keyPointsString = implode(',', $key_points); // Convert array to string
    } else {
        $keyPointsString = $key_points; // Already a string
    }

    // Handle file upload for the image
    $mem_image = $_FILES['mem_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($mem_image);

    if (!empty($mem_image)) {
        if (move_uploaded_file($_FILES['mem_image']['tmp_name'], $target_file)) {
            // Update the plan in the database with the new image
            $update_sql = "UPDATE mem_plan SET title = ?, key_points = ?, price = ?, mem_image = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, 'ssssi', $title, $keyPointsString, $price, $mem_image, $id);
        } else {
            $errorMessage = "Failed to upload the image.";
        }
    } else {
        // Update the plan in the database without changing the image
        $update_sql = "UPDATE mem_plan SET title = ?, key_points = ?, price = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $title, $keyPointsString, $price, $id);
    }

    // Execute the update statement
    if (isset($stmt) && mysqli_stmt_execute($stmt)) {
        $successMessage = "Plan updated successfully!";
    } else {
        $errorMessage = isset($errorMessage) ? $errorMessage : "Error updating plan: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<style>
              .section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            margin-bottom: 15px;
        }
       
body {
    background-color: #f7fafc; /* bg-gray-100 */
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

/* Container for the entire layout */
.container {
    display: flex;
    min-height: 100vh;
}


    </style>
<?php
// Connect to the database
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


?>
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





    

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <div class="ml-64 p-4"> <!-- Left margin for sidebar -->
   
   <div class="max-w-2xl ml-64 p4">
       <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
           <div class="bg-gradient-to-r from-cyan-600 to-blue-800 p-6">
               <h3 class="text-3xl font-bold text-white">Add New Plan</h3>
           </div>

           <form action="process_add_plan.php" method="POST" enctype="multipart/form-data" class="p-2 space-y-2">
               <div>
                   <label for="title" class="text-lg font-semibold text-gray-700 mb-2 block">Plan Title</label>
                   <input type="text" name="title" id="title" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                       placeholder="Enter plan title" required>
               </div>

               <div>
               <label for="key_points">Key Points </label><br>
        <textarea id="key_points" name="key_points[]" rows="5" cols="30" required

                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                       placeholder="Enter key features" required></textarea><br><br>
               </div>

               <div>
                   <label for="price" class="text-lg font-semibold text-gray-700 mb-2 block">Price (Rs)</label>
                   <div class="relative">
                       <span class="absolute left-4 top-3 text-gray-500"></span>
                       <input type="number" name="price" id="price"
                           class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="99.99" required>
                   </div>
               </div>

               <div>
                   <label for="mem_image" class="text-lg font-semibold text-gray-700 mb-2 block">Plan Image</label>
                   <div class="flex justify-center px-2 py-2 border-2 border-dashed border-gray-300 rounded-xl">
                       <div class="text-center">
                           <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                           <div class="flex text-sm text-gray-600">
                               <label for="mem_image"
                                   class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-green-500">
                                   <span>Upload a file</span>
                                   <input id="mem_image" name="mem_image" type="file" class="sr-only" required>
                               </label>
                               <p class="pl-1">or drag and drop</p>
                           </div>
                           <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                       </div>
                   </div>
               </div>

               <div class="flex justify-end space-x-2 pt-4">
                   <button type="button" onclick="closeModal('addModal')"
                       class="px-6 py-3 rounded-full bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-all">
                       Cancel
                   </button>
                   <button type="submit"
                       class="px-6 py-3 rounded-full bg-blue-600 text-white font-medium hover:bg-blue-700 transform hover:-translate-y-0.5 transition-all duration-150">
                       Add Plan
                   </button>
               </div>
           </form>
       </div>
   </div>


<?php

// Fetch membership plans from the database
$sql = "SELECT * FROM mem_plan";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error retrieving membership plans: " . mysqli_error($conn));
}
?>



        <!-- Display Success or Error Message -->
        <?php if (!empty($successMessage)) : ?>
            <div class="mb-4 ml-64 p-10 p-4  text-green-700 rounded">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>

        <?php endif; ?>

        <div class="ml-64 p-10"> <!-- Left margin for sidebar -->
   <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
       <?php while ($plan = mysqli_fetch_assoc($result)) { ?>
           <div class="transform hover:scale-105 transition-transform duration-300 border border-gray-300 rounded-3xl shadow-lg overflow-hidden">
               <div class="relative h-48">
             
               <img 
    src="uploads/<?php echo htmlspecialchars($plan['mem_image']); ?>" 
    alt="<?php echo htmlspecialchars($plan['title']); ?>" 
    class="w-28 h-28 object-cover mx-auto">
       


                   <div class="absolute top-4 right-4">
                       <span class=" bg-blue-700 px-4 py-2 text-white rounded-full font-bold">
                           <?php echo htmlspecialchars($plan['price']); ?>
                       </span>
                   </div>
               </div>
               
               <div class="p-6">
                   <h3 class="text-xl font-bold text-black mb-2">
                       <?php echo htmlspecialchars($plan['title']); ?>
                   </h3>
                   
                   <p class="text-black text-sm mb-4">
                       <?php echo htmlspecialchars($plan['key_points']); ?>
                   </p>

                   <div class="flex justify-end space-x-3">
                       <button 
                           onclick="openEditModal(<?php echo htmlspecialchars(json_encode($plan)); ?>)" 
                           class="flex items-center px-6 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600"
                       >
                           <i class="fas fa-edit mr-2"></i>
                           Edit
                       </button>

                       <button 
                           onclick="deletePlan(<?php echo $plan['id']; ?>)" 
                           class="flex items-center px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600"
                       >
                           <i class="fas fa-trash mr-2"></i>
                           Delete
                       </button>
                   </div>
               </div>
           </div>
       <?php } ?>
   </div>
</div>
            
 
    
    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-bold mb-4">Edit Plan</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-4">
                    <label for="edit_title" class="block font-bold mb-2">Title</label>
                    <input type="text" name="title" id="edit_title" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="edit_key_points" class="block font-bold mb-2">Key Points</label>
                    <textarea name="key_points" id="edit_key_points" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit_price" class="block font-bold mb-2">Price</label>
                    <input type="number" name="price" id="edit_price" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="edit_mem_image" class="block font-bold mb-2">Image</label>
                    <input type="file" name="mem_image" id="edit_mem_image" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="flex justify-between">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg">Save Changes</button>
                    <button type="button" onclick="closeModal('editModal')" class="bg-gray-300 text-black px-6 py-2 rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function openEditModal(plan) {
            document.getElementById('edit_id').value = plan.id;
            document.getElementById('edit_title').value = plan.title;
            document.getElementById('edit_key_points').value = plan.key_points;
            document.getElementById('edit_price').value = plan.price;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function deletePlan(id) {
            if (confirm('Are you sure you want to delete this plan?')) {
                window.location.href = 'delete_plan.php?id=' + id;
            }
        }
    </script>
</body>
</html>
