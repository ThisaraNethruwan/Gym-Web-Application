<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activities</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: 
                linear-gradient(rgba(0, 0, 0, 0.68), rgba(0, 0, 0, 0.66)), 
                url('blognew.jpeg'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            height: 100vh; 
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: white;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(74, 222, 128, 0.5) rgba(0, 0, 0, 0.2);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(74, 222, 128, 0.5);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(74, 222, 128, 0.7);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <header class="bg-black/90 text-white py-4 sticky top-0 z-50 backdrop-blur-sm">
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.php" class="active">About Us</a></li>
            <li><a href="memberships01.php">Memberships</a></li>
            <li><a href="activities_02.php">Activities</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Signin</a></li>
            <li><a href="blogs.php">Blogs</a></li>
        </ul>
    </nav>
    </header>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "studentform";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch activities
    $sql = "SELECT title, description, media_type, media_path, updated_at FROM activities";
    $result = $conn->query($sql);
    $activities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
    }
    ?>

    <!-- Main Content -->
    <div class="min-h-screen py-12 px-4">
        <div class="container mx-auto">
            <h1 class="text-5xl font-bold text-center text-white opacity-75 hover:opacity-100 transition-opacity duration-300">
                Select Your Activity
            </h1><br>
            <div class="w-24 h-1 bg-blue-500 mx-auto"></div>
            </div><br>
            <div class="relative max-w-[90vw] mx-auto">
                <button onclick="slide('left')" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 bg-black/50 hover:bg-black/80 text-white p-4 rounded-full z-10 transition-all duration-300 hover:scale-110">
                    <i class="fas fa-chevron-left text-2xl"></i>
                </button>

                <div class="overflow-hidden">
                    <div id="slider" class="flex transition-transform duration-500 ease-out space-x-6">
                        <?php foreach ($activities as $activity): ?>
                            <div class="flex-none w-[calc(25%-1.25rem)]">
                                <div class="bg-black/30 backdrop-blur-lg rounded-2xl h-[490px] border-2 border-gray-800 group shadow-2xl hover:shadow-green-500/30 overflow-hidden">
                                    <div class="h-48 overflow-hidden">
                                        <?php if ($activity['media_type'] === 'video'): ?>
                                            <video class="w-full h-full object-cover" autoplay muted loop>
                                                <source src="<?php echo htmlspecialchars($activity['media_path']); ?>" type="video/mp4">
                                            </video>
                                        <?php else: ?>
                                            <img src="<?php echo htmlspecialchars($activity['media_path']); ?>" 
                                                 alt="<?php echo htmlspecialchars($activity['title']); ?>"
                                                 class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-6 h-[276px] flex flex-col">
                                        <h3 class="text-2xl font-bold mb-3 text-green-400 group-hover:text-green-300 transition-colors duration-300">
                                            <?php echo htmlspecialchars($activity['title']); ?>
                                        </h3>
                                        <div class="h-[140px] overflow-y-auto mb-4 custom-scrollbar">
                                            <p class="text-gray-300">
                                                <?php echo htmlspecialchars($activity['description']); ?>
                                            </p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="register.php" 
                                               class="inline-block w-full bg-blue-600 hover:bg-blue-700 hover:text-white text-white font-bold py-2 px-6 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-blue-500/50 text-center">
                                                Select
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button onclick="slide('right')" class="absolute right-0 top-1/2 translate-x-12 bg-black/50 hover:bg-black/80 text-white p-4 rounded-full z-10 transition-all duration-300 hover:scale-110">
                    <i class="fas fa-chevron-right text-2xl"></i>
                </button>

                <div class="flex justify-center mt-4 space-x-2">
                    <?php 
                    $totalSlides = ceil(count($activities) / 4);
                    for ($i = 0; $i < $totalSlides; $i++): 
                    ?>
                        <button onclick="goToSlide(<?php echo $i; ?>)" 
                                class="w-3 h-3 rounded-full bg-gray-600 hover:bg-green-400 transition-colors duration-300"
                                id="dot-<?php echo $i; ?>">
                        </button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slider = document.getElementById('slider');
        const totalSlides = Math.ceil(<?php echo count($activities); ?> / 4);

        function updateDots() {
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.getElementById(`dot-${i}`);
                dot.classList.toggle('bg-green-400', i === currentSlide);
                dot.classList.toggle('bg-gray-600', i !== currentSlide);
            }
        }

        function slide(direction) {
            currentSlide = Math.max(0, Math.min(totalSlides - 1, currentSlide + (direction === 'right' ? 1 : -1)));
            updateSlider();
        }

        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            updateSlider();
        }

        function updateSlider() {
            slider.style.transform = `translateX(${currentSlide * -100}%)`;
            updateDots();
        }

        updateDots();
        
        
    </script>
</body>
</html>
