<?php
include_once 'db_function/db.php';

// ANNOUNCEMENTS
$stmt = $pdo->prepare("SELECT * FROM announcements WHERE status = 'published' ORDER BY posted_at DESC LIMIT 3");
$stmt->execute();
$announcements = $stmt->fetchAll();

// TOPICS
$stmt2 = $pdo->prepare("SELECT * FROM topics WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
$stmt2->execute();
$topics = $stmt2->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>People's Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        .section-title {
            font-weight: 700;
            color: #002366;
            margin-bottom: 30px;
        }

        /* NAVBAR */
        .navbar {
            transition: all 0.4s ease-in-out;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled {
            background-color: #ffffff !important;
            padding: 5px 0;
        }

        .nav-link {
            position: relative;
            font-weight: 500;
            color: #333;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: #0d6efd;
            transition: 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        .nav-link.active {
            color: #0d6efd !important;
        }

        /* HERO SLIDER */
        .carousel-item {
            height: 80vh;
            color: white;
        }

        .hero-slide {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-1 {
            background: linear-gradient(135deg, #002366, #0d6efd);
        }

        .hero-2 {
            background: linear-gradient(135deg, #0d6efd, #198754);
        }

        .hero-3 {
            background: linear-gradient(135deg, #002366, #ffc107);
            color: #002366;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .hero-slide {
            height: 80vh;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45); /* dark overlay for readability */
        }

        /* CARDS */
        .card-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
            height: 100%;
        }

        .card-box:hover {
            transform: translateY(-5px);
        }

        /* CTA */
        .cta {
            background: #002366;
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-top: 60px;
        }

        .btn-gold {
            background: #ffc107;
            color: #002366;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 8px;
        }

        .btn-gold:hover {
            background: white;
        }
    </style>
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO SLIDER WITH BACKGROUND IMAGES -->
<div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-inner">

        <!-- SLIDE 1 -->
        <div class="carousel-item active">
            <div class="hero-slide d-flex align-items-center justify-content-center text-center"
                 style="background: url('assets/img/slide1.jpg') center/cover no-repeat;">
                
                <div class="overlay"></div>

                <div class="container text-white position-relative">
                    <h1 class="fw-bold">Ozamiz City People's MPC</h1>
                    <p>Your Trusted Cooperative for Financial Growth & Stability</p>
                </div>
            </div>
        </div>

        <!-- SLIDE 2 -->
        <div class="carousel-item">
            <div class="hero-slide d-flex align-items-center justify-content-center text-center"
                 style="background: url('assets/img/slide2.jpg') center/cover no-repeat;">
                
                <div class="overlay"></div>

                <div class="container text-white position-relative">
                    <h1 class="fw-bold">Secure Savings Programs</h1>
                    <p>Grow your money safely with trusted cooperative services</p>
                </div>
            </div>
        </div>

        <!-- SLIDE 3 -->
        <div class="carousel-item">
            <div class="hero-slide d-flex align-items-center justify-content-center text-center"
                 style="background: url('assets/img/slide3.jpg') center/cover no-repeat;">
                
                <div class="overlay"></div>

                <div class="container text-white position-relative">
                    <h1 class="fw-bold">Accessible & Fast Loans</h1>
                    <p>Helping members achieve financial freedom</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</div>

<!-- ANNOUNCEMENTS -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">📢 Latest Announcements</h2>

        <div class="row g-4">
            <?php foreach ($announcements as $row): ?>
                <div class="col-md-4">
                    <div class="card-box">
                        <h5><?= htmlspecialchars($row['title']) ?></h5>
                        <small class="text-muted">
                            <?= date('F d, Y', strtotime($row['posted_at'])) ?>
                        </small>
                        <p class="mt-2">
                            <?= substr(strip_tags($row['content']), 0, 100) ?>...
                        </p>
                        <a href="announcement.php" class="btn btn-sm btn-primary">Read More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="announcement.php" class="btn btn-outline-primary">View All</a>
        </div>
    </div>
</section>

<!-- TOPICS -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title">📚 Latest Topics</h2>

        <div class="row g-4">
            <?php foreach ($topics as $t): ?>
                <div class="col-md-4">
                    <div class="card-box">
                        <h5><?= htmlspecialchars($t['title']) ?></h5>
                        <p><?= substr(strip_tags($t['description']), 0, 120) ?>...</p>
                        <a href="topics.php" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="topics.php" class="btn btn-outline-primary">View All Topics</a>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title">🏦 Products & Services</h2>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card-box text-center">
                    <i class="bi bi-bank fs-1 text-primary"></i>
                    <h6 class="mt-2">Loans</h6>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-box text-center">
                    <i class="bi bi-piggy-bank fs-1 text-primary"></i>
                    <h6 class="mt-2">Savings</h6>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="productservices.php" class="btn btn-outline-primary">View Full Services</a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <div class="container">
        <h2>Become a Member Today</h2>
        <p>Enjoy loans, savings, and financial growth opportunities.</p>
        <a href="membership.php" class="btn btn-gold mt-3">Apply Now</a>
    </div>
</section>

<!-- CONTACT -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="section-title">📞 Contact Us</h2>
        <p>We are here to assist you anytime.</p>
        <a href="contact.php" class="btn btn-primary mt-3">Send Message</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
});
</script>

</body>
</html>