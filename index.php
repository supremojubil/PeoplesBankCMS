<?php
include_once 'db_function/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People's Bank</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        /* Smooth transition for the navbar background */
        .navbar {
            transition: all 0.4s ease-in-out;
            padding: 15px 0; /* Slightly larger initial padding */
        }

        /* Effect when scrolling down */
        .navbar.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 5px 0; /* Shrink effect */
        }

        /* Animated Underline for Nav Links */
        .nav-link {
            position: relative;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #0d6efd; /* Your text-primary color */
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        .hero {
            background: #0d47a1;
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }
        .hero-img {
            height: 500px;
            object-fit: cover;
            filter: brightness(60%);
        }

        .carousel-caption {
            bottom: 40%;
        }

        .carousel-caption h1 {
            font-size: 48px;
            font-weight: bold;
        }

        .carousel-caption p {
            font-size: 18px;
        }

        section {
            padding: 60px 0;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer {
            background: #0a2540;
            color: #fff;
            padding: 40px 0;
            font-size: 14px;
        }
        .footer img {
            border-radius: 8px;
        }
        .footer a:hover {
            opacity: 0.8;
        }
        
    </style>
</head>

<body>

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>

<!-- HERO SLIDER -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="assets/img/slide1.jpg" class="d-block w-100 hero-img" alt="Banking 1">
            <div class="carousel-caption">
                <h1>Welcome to People's Bank</h1>
                <p>Your trusted financial partner</p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="assets/img/slide2.jpg" class="d-block w-100 hero-img" alt="Banking 2">
            <div class="carousel-caption">
                <h1>Secure Banking Experience</h1>
                <p>Safe, fast, and reliable services</p>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="assets/img/slide3.jpg" class="d-block w-100 hero-img" alt="Banking 3">
            <div class="carousel-caption">
                <h1>Grow Your Savings</h1>
                <p>Better future starts with smart banking</p>
            </div>
        </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>

<!-- ABOUT US -->
<section id="about" class="bg-light">
    <div class="container">
        <h2 class="section-title">About Us</h2>
        <p>
            People's Bank is committed to providing safe, reliable, and modern financial services
            for individuals and businesses.
        </p>
    </div>
</section>

<!-- WHAT'S NEW -->
<section id="news">
    <div class="container">
        <h2 class="section-title">What's New</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>New Mobile App</h5>
                    <p>Faster and secure banking experience.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Loan Promo</h5>
                    <p>Low interest rates for limited time.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Branch Expansion</h5>
                    <p>More branches nationwide.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRODUCTS & SERVICES -->
<section id="services" class="bg-light">
    <div class="container">
        <h2 class="section-title">Products & Services</h2>

        <ul>
            <li>Savings Account</li>
            <li>Checking Account</li>
            <li>Loans</li>
            <li>Online Banking</li>
            <li>ATM Services</li>
        </ul>
    </div>
</section>

<!-- MEMBERSHIP -->
<section id="membership">
    <div class="container">
        <h2 class="section-title">Membership</h2>
        <p>
            Become a member of People's Bank and enjoy exclusive financial benefits,
            lower fees, and priority services.
        </p>

        <button class="btn btn-primary">Apply Now</button>
    </div>
</section>

<!-- CONTACT -->
<section id="contact" class="bg-light">
    <div class="container">
        <h2 class="section-title">Contact Us</h2>

        <form>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Your Name">
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email">
            </div>

            <div class="mb-3">
                <textarea class="form-control" placeholder="Message"></textarea>
            </div>

            <button class="btn btn-primary">Send Message</button>
        </form>
    </div>
</section>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>