<?php
// Get the current page filename (e.g., about.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNavbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary" href="index.php">
            <img src="assets/img/peoplesbanklogo.jpg" alt="Logo" style="height: 60px; width: auto; margin-right: 15px;">
            <span style="font-size: 16px; font-weight: 700; line-height: 1.2; max-width: 200px;">
                OZAMIZ CITY PEOPLE'S <br> MULTI PURPOSE COOPERATIVE
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link mx-2" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="index.php#news">What's New</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="index.php#services">Products & Services</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="index.php#membership">Membership</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="index.php#contact">Contact Us</a></li>
            </ul>
        </div>
    </div>
</nav>