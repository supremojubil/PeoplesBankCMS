<?php
// Get the current page filename (e.g., about.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top" id="mainNavbar">
    <div class="container-fluid px-0" style="justify-content: space-between;">
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary" href="index.php">
            <img src="assets/img/peoplesbanklogo.jpg" alt="Logo" style="max-height: 90px; width: auto; margin-right: 18px;">
            <span style="font-size: 16px; font-weight: 700; line-height: 1.2; max-width: 220px;">
                OZAMIZ CITY PEOPLE'S <br> MULTI PURPOSE COOPERATIVE
            </span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link mx-2" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="topics.php">Topics</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="index.php#news">What's New</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="productservices.php">Products & Services</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="membership.php">Membership</a></li>
                <li class="nav-item"><a class="nav-link mx-2" href="contact.php">Contact Us</a></li>
            </ul>
        </div>
    </div>
</nav>