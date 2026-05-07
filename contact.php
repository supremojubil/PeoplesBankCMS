<?php
session_start();
include_once 'db_function/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Ozamiz City People's MPC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f8;
            color: #1f2a44;
        }
        .page-header {
            background: linear-gradient(135deg, #002366 0%, #0d6efd 100%);
            color: #fff;
            padding: 80px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .page-header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        .page-header p {
            font-size: 1.3rem;
            opacity: 0.95;
        }
        /* Navbar styles */
        .navbar {
            transition: all 0.4s ease-in-out;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 5px 0;
        }

        /* NAV LINK BASE */
        .nav-link {
            position: relative;
            font-weight: 500;
            transition: color 0.3s ease;
            color: #333;
        }

        /* HOVER ANIMATION */
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #0d6efd;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        /* ACTIVE LINK (IMPORTANT) */
        .nav-link.active {
            color: #0d6efd !important;
        }

        .nav-link.active::after {
            width: 100%;
        }
        .section-padding {
            padding: 60px 0;
        }
        .contact-card,
        .branch-card,
        .info-panel {
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 20px 50px rgba(15, 43, 86, 0.08);
            border: 1px solid rgba(15, 43, 86, 0.08);
        }
        .contact-card {
            padding: 40px;
        }
        .branch-card {
            padding: 28px;
            transition: transform 0.25s ease, border-color 0.25s ease;
        }
        .branch-card:hover {
            transform: translateY(-6px);
            border-color: #0d6efd;
        }
        .branch-card h3 {
            font-size: 1.25rem;
            margin-bottom: 12px;
        }
        .branch-card p {
            color: #495057;
            margin-bottom: 16px;
        }
        .branch-card .badge {
            font-size: 0.85rem;
            padding: 0.65em 0.9em;
        }
        .info-panel {
            padding: 34px;
        }
        .info-panel h2 {
            margin-bottom: 18px;
            font-weight: 700;
        }
        .info-panel p {
            color: #5a677d;
            line-height: 1.8;
        }
        .info-panel ul {
            list-style: none;
            padding-left: 0;
            margin-top: 24px;
        }
        .info-panel ul li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
            color: #36435f;
        }
        .info-panel ul li .bi {
            color: #0d6efd;
            margin-top: 3px;
        }
        .form-control,
        .form-control:focus,
        .form-select {
            border-radius: 12px;
            border: 1px solid #ced4da;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.18);
        }
        .btn-primary {
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
        }
        .branch-list .badge {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }
        .map-placeholder {
            min-height: 320px;
            border-radius: 18px;
            overflow: hidden;
            background: #eef4ff;
            border: 1px solid rgba(13,110,253,0.15);
        }
        .map-placeholder iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        .contact-detail {
            margin-bottom: 24px;
        }
        .contact-detail .bi {
            color: #0d6efd;
            font-size: 1.3rem;
            margin-right: 14px;
        }
        @media (max-width: 991px) {
            .page-header {
                padding: 60px 0;
            }
            .page-header h1 {
                font-size: 2.4rem;
            }
        }
        @media (max-width: 767px) {
            .contact-card,
            .info-panel,
            .branch-card {
                padding: 24px;
            }
            .page-header p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="page-header">
    <div class="container">
        <span class="badge bg-white text-primary mb-3 py-2 px-3">Contact Us</span>
        <h1>We are here to help you</h1>
        <p>Reach out to Ozamiz City People's Multi-Purpose Cooperative for branch support, membership questions, loan inquiries, or general assistance.</p>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-card">
                    <h2 class="mb-4">Branch Support</h2>
                    <div class="contact-detail d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill"></i>
                        <div>
                            <h6 class="mb-1">Main Branch Location</h6>
                            <p class="mb-0">City Public Mall Drive, P-1 San Roque, Ozamiz City</p>
                        </div>
                    </div>
                    <div class="contact-detail d-flex align-items-start">
                        <i class="bi bi-telephone-fill"></i>
                        <div>
                            <h6 class="mb-1">Phone</h6>
                            <p class="mb-0">(088) 521-0296 / 521 - 4842</p>
                        </div>
                    </div>
                    <div class="contact-detail d-flex align-items-start">
                        <i class="bi bi-phone-fill"></i>
                        <div>
                            <h6 class="mb-1">Mobile</h6>
                            <p class="mb-0">0997-164-5105 / 0909-198-4912</p>
                        </div>
                    </div>
                    <div class="contact-detail d-flex align-items-start">
                        <i class="bi bi-envelope-fill"></i>
                        <div>
                            <h6 class="mb-1">Email</h6>
                            <p class="mb-0">ocpcozamiz@gmail.com</p>
                        </div>
                    </div>
                    <div class="contact-detail d-flex align-items-start">
                        <i class="bi bi-clock-fill"></i>
                        <div>
                            <h6 class="mb-1">Branch Hours</h6>
                            <p class="mb-0">Monday to Friday, 8:30 AM – 5:00 PM</p>
                        </div>
                    </div>
                    <div class="map-placeholder mt-4">
                        <iframe src="https://maps.google.com/maps?q=City%20Public%20Mall%20Drive%2C%20P-1%20San%20Roque%2C%20Ozamiz%20City&t=&z=15&ie=UTF8&iwloc=&output=embed" allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="contact-card">
                    <h2 class="mb-4">Send us a message</h2>
                    <!-- SUCCESS -->
                     <?php if(isset($_SESSION['contact_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill"></i>
                        <?php
                            echo $_SESSION['contact_success'];
                            unset($_SESSION['contact_success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            
            <!-- ERROR -->
            <?php if(isset($_SESSION['contact_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?php
                        echo $_SESSION['contact_error'];
                        unset($_SESSION['contact_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- VALIDATION -->
            <?php if(isset($_SESSION['contact_errors'])): ?>
                <div class="alert alert-warning alert-dismissible fade show">
                    <strong>Please fix the following:</strong>

                    <ul class="mb-0 mt-2">
                        <?php foreach($_SESSION['contact_errors'] as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <?php unset($_SESSION['contact_errors']); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

                    
                    <form action="db_function/process_contact.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="fullname" class="form-control" placeholder="Your full name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Your email address" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="Mobile or landline">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Branch Inquiry</label>
                                <select name="branch" class="form-select" required>
                                    <option selected value="City Public Mall Drive, P-1 San Roque, Ozamiz City">Main Branch - City Public Mall Drive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="6" placeholder="Tell us how we can help you" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Submit Inquiry</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-5 g-4">
            <div class="col-lg-6">
                <div class="branch-card">
                    <h3>Main Branch</h3>
                    <p>City Public Mall Drive, P-1 San Roque, Ozamiz City</p>
                    <span class="badge bg-primary">Main Branch</span>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="info-panel">
                    <h2>Main Branch Location</h2>
                    <p>We currently operate from a single main branch in Ozamiz City. Contact us using the details above, and our team will assist you with all inquiries.</p>
                    <ul class="branch-list">
                        <li><i class="bi bi-check-circle-fill"></i> City Public Mall Drive, P-1 San Roque, Ozamiz City</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
