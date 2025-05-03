<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "studentform");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming user is logged in and their username is stored in the session
$username = $_SESSION['username']; // Ensure this is set upon login
$activity = $_POST['activity'];
$trainer = $_POST['trainer'];
$price = $_POST['price'];
// Assuming you have start_time and end_time from the user input
$user_id = $_SESSION['user_id']; // Make sure you have user ID from session after login
$activity_id = $_POST['activity_id']; // Make sure you pass this when selecting an activity
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

$sql = "INSERT INTO reservations (user_id, activity_id, start_time, end_time) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiss", $user_id, $activity_id, $start_time, $end_time);

if (mysqli_stmt_execute($stmt)) {
    echo "Reservation successful!";
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserve Activity</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .time-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .time-buttons button {
            padding: 10px 15px;
            background-color: #58ff33;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .time-buttons button:hover {
            background-color: #45e02a;
        }
        .selected {
            background-color: #ff5733; /* Color for selected time */
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html" class="active">About Us</a></li>
                <li><a href="memberships01.html">Memberships</a></li>
                <li><a href="activities.html">Activities</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Signin</a></li>
            </ul>
        </nav>
    </header>

    <section class="reservation-section">
        <h2>Reserve Your Activity: <?php echo ucfirst($activity); ?></h2>
        <p><strong>Trainer:</strong> <?php echo $trainer; ?></p>
        <p><strong>Price per Hour:</strong> $<?php echo $price; ?></p>

        <h3>Select Start Time</h3>
        <button id="startTimeButton">Choose Start Time</button>

        <h3>Select End Time</h3>
        <button id="endTimeButton">Choose End Time</button>

        <form action="process_reservation.php" method="POST" id="reservationForm">
            <input type="hidden" name="activity" value="<?php echo $activity; ?>">
            <input type="hidden" name="trainer" value="<?php echo $trainer; ?>">
            <input type="hidden" name="price" value="<?php echo $price; ?>">
            <input type="hidden" name="start_time" id="start_time">
            <input type="hidden" name="end_time" id="end_time">

            <input type="submit" value="Reserve" id="reserveButton" disabled>
        </form>
    </section>

    <!-- Start Time Modal -->
    <div id="startTimeModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeStartModal">&times;</span>
            <h3>Select Start Time</h3>
            <div class="time-buttons" id="startTimeButtons">
                <?php for ($hour = 8; $hour <= 20; $hour++): ?>
                    <?php for ($minute = 0; $minute < 60; $minute += 30): ?>
                        <?php $time = sprintf('%02d:%02d', $hour, $minute); ?>
                        <button type="button" onclick="selectStartTime('<?php echo $time; ?>')"><?php echo $time; ?></button>
                    <?php endfor; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- End Time Modal -->
    <div id="endTimeModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeEndModal">&times;</span>
            <h3>Select End Time</h3>
            <div class="time-buttons" id="endTimeButtons">
                <?php for ($hour = 8; $hour <= 20; $hour++): ?>
                    <?php for ($minute = 0; $minute < 60; $minute += 30): ?>
                        <?php $time = sprintf('%02d:%02d', $hour, $minute); ?>
                        <button type="button" onclick="selectEndTime('<?php echo $time; ?>')"><?php echo $time; ?></button>
                    <?php endfor; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <script>
        let startTime = null;
        let endTime = null;

        // Open modals
        document.getElementById("startTimeButton").onclick = function() {
            document.getElementById("startTimeModal").style.display = "block";
        }

        document.getElementById("endTimeButton").onclick = function() {
            document.getElementById("endTimeModal").style.display = "block";
        }

        // Close modals
        document.getElementById("closeStartModal").onclick = function() {
            document.getElementById("startTimeModal").style.display = "none";
        }

        document.getElementById("closeEndModal").onclick = function() {
            document.getElementById("endTimeModal").style.display = "none";
        }

        // Close modals when clicking outside of them
        window.onclick = function(event) {
            if (event.target == document.getElementById("startTimeModal")) {
                document.getElementById("startTimeModal").style.display = "none";
            }
            if (event.target == document.getElementById("endTimeModal")) {
                document.getElementById("endTimeModal").style.display = "none";
            }
        }

        function selectStartTime(time) {
            startTime = time;
            document.getElementById('start_time').value = time;

            // Highlight selected button
            document.getElementById('startTimeButtons').querySelectorAll('button').forEach(button => {
                button.classList.remove('selected');
            });
            const selectedButton = Array.from(document.getElementById('startTimeButtons').children).find(button => button.textContent === time);
            selectedButton.classList.add('selected');

            // Close modal
            document.getElementById("startTimeModal").style.display = "none";

            // Enable reserve button if both times are selected
            checkIfReadyToReserve();
        }

        function selectEndTime(time) {
            endTime = time;
            document.getElementById('end_time').value = time;

            // Highlight selected button
            document.getElementById('endTimeButtons').querySelectorAll('button').forEach(button => {
                button.classList.remove('selected');
            });
            const selectedButton = Array.from(document.getElementById('endTimeButtons').children).find(button => button.textContent === time);
            selectedButton.classList.add('selected');

            // Close modal
            document.getElementById("endTimeModal").style.display = "none";

            // Enable reserve button if both times are selected
            checkIfReadyToReserve();
        }

        function checkIfReadyToReserve() {
            const reserveButton = document.getElementById('reserveButton');
            if (startTime && endTime) {
                reserveButton.disabled = false; // Enable button if both times are selected
            } else {
                reserveButton.disabled = true; // Disable button if either time is not selected
            }
        }
    </script>
</body>
</html>
