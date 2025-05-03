<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            height: 90vh; 
            margin: 0;
        }
        .scroll-smooth {
            scroll-behavior: smooth;
        }
        .gallery-scroll::-webkit-scrollbar {
            display: none;
        }
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .thumbnail {
            transition: all 0.3s ease;
        }
        .thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }
      
    </style>
    <title>Gallery</title>
</head>
<body class="bg-gray-900">
    <!-- Navigation -->
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

    <!-- Gallery Section -->
    <div class="max-w-7xl mx-auto px-4">
        <br><br><br><br><br>
        <h2 class="text-5xl font-bold text-white text-center tracking-wider">Our Gym Facilities</h2>
        <br>
        <div class="w-24 h-1 bg-blue-500 mx-auto"></div><br><br>
        
        <div class="relative">
            <!-- Left Arrow -->
            <button onclick="scrollGallery('left')" 
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 bg-black/50 hover:bg-black/80 text-white p-4 rounded-full z-10 transition-all duration-300 hover:scale-110">
                <i class="fas fa-chevron-left text-2xl"></i>
            </button>

            <!-- Gallery Scroll Container -->
            <div id="gallery" class="gallery-scroll flex gap-6 overflow-x-hidden scroll-smooth relative mx-8">
                <?php
                $conn = mysqli_connect("localhost", "root", "", "studentform");
                if (!$conn) {
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM gallery ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                        <div class="flex-none w-[375px] slide-in">
                            <div class="relative group">
                                <img src="uploads/' . htmlspecialchars($row['image_name']) . '" 
                                     alt="Gym Image" 
                                     class="thumbnail w-[380px] h-[300px] object-cover rounded-2xl border-2 border-white hover:border-blue-400" />
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 rounded-2xl"></div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p class="text-white text-center">No images available in the gallery.</p>';
                }
                mysqli_close($conn);
                ?>
            </div>

            <!-- Right Arrow -->
            <button onclick="scrollGallery('right')" 
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 bg-black/50 hover:bg-black/80 text-white p-4 rounded-full z-10 transition-all duration-300 hover:scale-110">
                <i class="fas fa-chevron-right text-2xl"></i>
            </button>
        </div>

        <!-- Thumbnails -->
        <div class="flex justify-center mt-8 space-x-2">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "studentform");
            if ($conn) {
                $sql = "SELECT * FROM gallery ORDER BY id DESC";
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                    $index = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                        <div onclick="goToSlide(' . $index . ')" class="cursor-pointer">
                            <img src="uploads/' . htmlspecialchars($row['image_name']) . '" 
                                 alt="Thumbnail" 
                                 class="w-16 h-12 object-cover rounded-lg border border-white hover:border-blue-400 transition-all duration-300" />
                        </div>';
                        $index++;
                    }
                }
                mysqli_close($conn);
            }
            ?>
        </div>
    </div>

    <script>
        let autoScrollInterval;
        const gallery = document.getElementById('gallery');
        let currentIndex = 0;
        
        function scrollGallery(direction) {
            stopAutoScroll();
            const scrollAmount = 375 + 24; // Image width + gap
            
            if (direction === 'left') {
                currentIndex = Math.max(0, currentIndex - 3);
            } else {
                currentIndex++;
            }
            
            const maxIndex = document.querySelectorAll('.slide-in').length - 3;
            if (currentIndex > maxIndex) {
                currentIndex = 0;
            }
            
            gallery.scrollTo({
                left: currentIndex * scrollAmount,
                behavior: 'smooth'
            });
            
            startAutoScroll();
        }

        function goToSlide(index) {
            stopAutoScroll();
            currentIndex = index;
            const scrollAmount = 375 + 24; // Image width + gap
            gallery.scrollTo({
                left: index * scrollAmount,
                behavior: 'smooth'
            });
            startAutoScroll();
        }

        function startAutoScroll() {
            stopAutoScroll();
            autoScrollInterval = setInterval(() => {
                scrollGallery('right');
            }, 3000);
        }

        function stopAutoScroll() {
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
            }
        }

        // Start auto-scroll when page loads
        startAutoScroll();

        // Stop auto-scroll when user interacts with gallery
        gallery.addEventListener('mouseenter', stopAutoScroll);
        gallery.addEventListener('mouseleave', startAutoScroll);
    </script>
</body>
</html>