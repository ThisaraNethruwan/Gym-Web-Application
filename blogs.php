<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "studentform");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM blogs ORDER BY date_created DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $blogs = [];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Blog</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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

        .blog-carousel {
            transition: transform 0.5s ease-in-out;
        }

        .blog-card {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.5s ease-in-out;
           
        }

        .blog-card.active {
            opacity: 1;
            transform: scale(1);
        }

   

        .navigation-button:hover {
            transform: scale(1.1);
            background-color: rgba(84, 85, 85, 0.9);
        }

    
    </style>
</head>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="memberships01.php">Memberships</a></li>
            <li><a href="activities_02.php">Activities</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Signin</a></li>
            <li><a href="blogs.php">Blogs</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
   
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
         <br>  <br><br> <h1 class="text-5xl md:text-6xl font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-teal-500">
                Our Latest Blogs
            </h1>
            <p class="text-2sm text-gray-300 max-w-4xl mx-auto ">
                .....Discover inspiring stories, insights, and updates from our community.....
            </p>
        </div>
    </div>
<br>
    <!-- Blog Carousel Section -->
    <div class="relative w-7xl mx-auto px-6 sm:px-8 lg:px-14 ">
        <!-- Navigation Buttons -->
        <button onclick="navigateBlogs('prev')" class="navigation-button absolute left-0 top-1/2 bg-gray-900 p-4 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button onclick="navigateBlogs('next')" class="navigation-button absolute right-0 top-1/2  bg-gray-900 p-4 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Blog Cards Container -->
        <div class="blog-carousel overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out">
                <?php if (!empty($blogs)): ?>
                    <?php foreach ($blogs as $index => $blog): ?>
                        <article class="blog-card w-full md:w-1/4 flex-shrink-0 px-2 <?php echo $index < 4 ? 'active' : ''; ?>">
                            <div class="bg-black/50 backdrop-blur-sm border border-gray-800 rounded-xl overflow-hidden h-full">
                                <?php if (!empty($blog['media'])): ?>
                                    <div class="aspect-video overflow-hidden">
                                        <?php if (strpos($blog['media'], '.jpg') || strpos($blog['media'], '.png') || strpos($blog['media'], '.jpeg')): ?>
                                            <img src="<?php echo $blog['media']; ?>" alt="Blog Media" class="w-full h-full object-cover">
                                        <?php elseif (strpos($blog['media'], '.mp4') || strpos($blog['media'], '.avi') || strpos($blog['media'], '.mov')): ?>
                                            <video controls class="w-full h-full object-cover">
                                                <source src="<?php echo $blog['media']; ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="p-6">
                                    <h2 class="text-2xl font-semibold mb-4 text-green-400">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </h2>
                                    
                                    <p class="text-gray-300 mb-4">
                                        <?php echo htmlspecialchars($blog['description']); ?>
                                    </p>

                                    <div id="content-<?php echo $blog['id']; ?>" class="hidden">
                                        <div class="prose prose-invert max-w-none mt-4">
                                            <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
                                        </div>
                                    </div>

                                    <a
                                        id="btn-<?php echo $blog['id']; ?>"
                                        onclick="toggleContent(<?php echo $blog['id']; ?>)"
                                        class="mt-4 bg-black/30 hover:bg-black/30 text-white hover:text-green-400 rounded-lg transition-colors duration-200 inline-flex items-center space-x-2"
                                    >
                                        Read More.....
                                        </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 w-full">
                        <p class="text-xl text-gray-400">No blogs available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Blog Navigation Dots -->
        <div class="flex justify-center mt-6 space-x-2">
            <?php
            $totalPages = ceil(count($blogs) / 4);
            for ($i = 0; $i < $totalPages; $i++):
            ?>
                <button 
                    onclick="goToPage(<?php echo $i; ?>)"
                    class="w-3 h-3 rounded-full bg-gray-600 hover:bg-gray-400 transition-colors duration-200"
                    id="dot-<?php echo $i; ?>"
                >
                </button>
            <?php endfor; ?>
        </div>
    </div>

    <script>
        let currentPage = 0;
        const blogsPerPage = 4;
        const totalBlogs = <?php echo count($blogs); ?>;
        const totalPages = Math.ceil(totalBlogs / blogsPerPage);
        
        function navigateBlogs(direction) {
            const carousel = document.querySelector('.blog-carousel > div');
            const cards = document.querySelectorAll('.blog-card');
            
            if (direction === 'next' && currentPage < totalPages - 1) {
                currentPage++;
            } else if (direction === 'prev' && currentPage > 0) {
                currentPage--;
            }
            
            updateCarousel();
        }

        function goToPage(pageNumber) {
            currentPage = pageNumber;
            updateCarousel();
        }

        function updateCarousel() {
            const carousel = document.querySelector('.blog-carousel > div');
            const cards = document.querySelectorAll('.blog-card');
            const dots = document.querySelectorAll('[id^="dot-"]');
            
            // Update carousel position
            carousel.style.transform = `translateX(-${currentPage * 100}%)`;
            
            // Update active cards
            cards.forEach((card, index) => {
                if (index >= currentPage * blogsPerPage && index < (currentPage + 1) * blogsPerPage) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            });
            
            // Update navigation dots
            dots.forEach((dot, index) => {
                if (index === currentPage) {
                    dot.classList.add('bg-green-500');
                    dot.classList.remove('bg-gray-600');
                } else {
                    dot.classList.add('bg-gray-600');
                    dot.classList.remove('bg-green-500');
                }
            });
        }

        function toggleContent(blogId) {
            const content = document.getElementById(`content-${blogId}`);
            const btn = document.getElementById(`btn-${blogId}`);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                btn.innerText = 'Read Less';
            } else {
                content.classList.add('hidden');
                btn.innerText = 'Read More';
            }
        }

        // Initialize the carousel
        updateCarousel();
    </script>
</body>
</html>