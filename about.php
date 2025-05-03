sele<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Fitness Center</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">

</head>
<body class="about-page">

<header>
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

<section class="about-section">
    <div class="about-content">
        <h1>About Our Fitness Center</h1>
        <p>Welcome to our state-of-the-art fitness center where we believe in more than just fitness. Our center is a community of individuals driven to achieve their personal best. With cutting-edge equipment, expert trainers, and a wide variety of fitness programs tailored to all fitness levels, we are here to help you succeed. Whether you are looking for high-intensity workouts, strength training, yoga, or Pilates, we’ve got it all under one roof.</p>
        <p>At our fitness center, we strive to foster a supportive environment where everyone feels welcome and motivated to pursue their health and wellness goals. Our facility is equipped with advanced technology and amenities that not only enhance your workout experience but also ensure that your journey to fitness is enjoyable and rewarding. Join us today and be part of a fitness community that values strength, health, and well-being.</p>
    </div>
</section>

<section class="about-caption">
    <h2>"The Only Bad Workout Is</h2>
    <h3>The One That Did't Happen"</h3>
    
</section>
<div class="scroll-down">
    <span>Scroll Down</span>
</div>
<section class="trainers-section">
    <h2>Meet Our Qualified Staff</h2>
   
    <div class="trainers-grid">
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "studentform");

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch trainer details where account_type is 'Staff'
        $sql = "SELECT username, description, profile_image FROM users WHERE account_type = 'Staff'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Loop through the rows and display trainer information
            while($row = $result->fetch_assoc()) {
                echo '<div class="trainer-discription">';
                // Display profile image, ensure the file path is correct
                echo '<img src="uploads/' . $row['profile_image'] . '" alt="Trainer">';
                // Display username
                echo '<h3>' . $row['username'] . '</h3>';
                // Display description
                echo '<p>' . $row['description'] . '</p>';
                echo '</div>';
            }
        } else {
            echo "No staff found.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div> 
</section>


<footer class="footer">
  <div class="footer-content">
    <div class="footer-brand">
      <h2>FITZONE <span class="highlight">FITNESS CENTER</span></h2>
      <p>YOUR PARTNER IN FITNESS</p>
      <p>
        Our premier destination for achieving your fitness goals. Whether you're a beginner or a seasoned athlete, we offer state-of-the-art equipment, personalized training programs, and a supportive community to help you stay motivated and healthy.
      </p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
        <a href="#"><i class="fas fa-map-marker-alt"></i></a>
        <a href="#"><i class="fas fa-envelope"></i></a>
      </div>
    </div>
    <div class="footer-links">
      <div class="link-section">
        <h3>Site Map</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="blogs.php">News & Blogs</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="gallery.php">Gallery</a></li>
        </ul>
      </div>
      <div class="link-section">
        <h3>Other Services</h3>
        <ul>
          <li><a href="memberships01.php">Memberships</a></li>
          <li><a href="activities_02.php"> Activities</a></li>
        
        </ul>
      </div>
      <div class="link-section">
        <h3>Helpful Links</h3>
        <ul>
          <li><a href="contact.html">FAQs</a></li>
          <li><a href="contact.html">Support</a></li>
        </ul>
      </div>
      <div class="link-section contact-section">
        <h3>Contact Us</h3>
        <p><i class="fas fa-envelope"></i> fitzone@gmail.com</p>
        <p><i class="fas fa-phone-alt"></i> +94 112 695 331</p>
        <p><i class="fas fa-map-marker-alt"></i> 42 1/1 Maitland Crescent, Kurunegala</p>
      </div>
    </div>
  </div>
</footer>
  
<style>
body {
margin: 0;
padding: 0;
display: flex;
flex-direction: column;
min-height: 100vh;
box-sizing: border-box;

}

main {
flex: 1;
}

.footer {

background-color: #000000cb;
color: #ffffff;
padding: 40px 20px;
width: 97%; 
display: flex;
justify-content: space-between;
flex-wrap: wrap;
position: relative; 
left: 0px;
right: 0px 
}

.footer-content {
display: flex;
flex-wrap: wrap;
width: 100%;
}

.footer-brand {
flex: 1;
min-width: 300px;
}

.footer-brand h2 {
font-size: 2em;
}

.footer-brand .highlight {
color: #48c902bd;
}

.footer-brand p {
margin: 10px 0;
line-height: 1.5;
}

.social-icons {
margin-top: 20px;
}

.social-icons a {
color: #ffffff;
margin-right: 10px;
font-size: 1.5em;
}

.footer-links {
flex: 2;
display: flex;
justify-content: space-around;
flex-wrap: wrap;
}

.link-section {
margin: 0 20px;
min-width: 150px;
}

.link-section h3 {
margin-bottom: 15px;
}

.link-section ul {
list-style: none;
padding: 0;
}

.link-section ul li {
margin-bottom: 10px;
}

.link-section ul li a {
color: #ffffff;
text-decoration: none;
transition: color 0.3s;
}

.link-section ul li a:hover {
color: #58ff33;
}

.contact-section p {
margin-bottom: 10px;
}

.contact-section i {
margin-right: 5px;
}

      body {
      
      background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.966)), url('about us.jpg');
background-size: cover;
background-position: center; 
background-repeat: no-repeat; 
  
}
.membership-img img {
    width: 28%;
    height: auto;
    margin-top: 20px;
    
}
</style>           
</body>
</html>
