<?php include_once 'db_function/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Ozamiz City People's MPC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .page-header { background: #f8f9fa; padding: 60px 0; border-bottom: 1px solid #dee2e6; }
        .section-padding { padding: 80px 0; }
        
        /* Background Image Section */
        .bg-concept {
            background: linear-gradient(rgba(0, 43, 91, 0.85), rgba(0, 43, 91, 0.85)), 
                        url('assets/img/pbbackground.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Parallax effect */
            color: white;
            padding: 100px 0;
        }

        /* Transparent Glass Cards for Core Values */
        .card-value { 
            background: rgba(255, 255, 255, 0.1); 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            color: white;
            transition: transform 0.3s; 
            backdrop-filter: blur(5px);
        }
        .card-value:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.2); }
        .card-value i { color: #ffc107 !important; } /* Gold icons for contrast */
        .card-value .text-muted { color: rgba(255,255,255,0.7) !important; }

        /* Board Image Styling */
        .bod-img { 
            width: 100%; max-width: 400px; height: auto; 
            border-radius: 12px; border: 4px solid #0d6efd; 
            cursor: pointer; transition: 0.3s;
        }
        .bod-img:hover { transform: scale(1.02); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        
        .carousel-item img { max-height: 85vh; object-fit: contain; background-color: #000; }
    </style>
</head>
<body>

<?php include_once 'includes/navbar.php'; ?>

<div class="page-header text-center">
    <div class="container">
        <h1 class="display-4 fw-bold text-primary">About Us</h1>
        <p class="lead text-muted">Learn more about our journey and the people behind the Cooperative.</p>
    </div>
</div>

<section class="bg-concept">
    <div class="container">
        <div class="row g-4 text-center mb-5">
             <div class="col-12 mb-4">
                <h2 class="text-warning fw-bold mb-3">OUR GOAL</h2>
                <p class="fs-4">FINANCIAL FREEDOM / KAHAMUGAWAY (SELF-SUFFICIENCY)</p>
            </div>
            <div class="col-md-6">
                <div class="p-4 h-100 border border-light rounded">
                    <h2 class="text-warning mb-3"><i class="bi bi-eye-fill"></i> Our Vision</h2>
                    <p style="font-size: 24px; font-weight: 500; line-height: 1.4;">
                        A sustainable and innovative cooperative of resilient and satisfied members.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 h-100 border border-light rounded">
                    <h2 class="text-warning mb-3"><i class="bi bi-rocket-takeoff-fill"></i> Our Mission</h2>
                    <p style="font-size: 24px; font-weight: 500; line-height: 1.4;">
                        Ozamiz City People’s Multi-Purpose Cooperative is committed to provide quality & responsive financial products and allied services for members and the community to enjoy a better quality of life.
                    </p>
                </div>
            </div>
        </div>

        <hr class="my-5 opacity-25">

        <div class="text-center">
            <h2 class="mb-3 fw-bold text-warning">CORE VALUES</h2>
            <div class="row g-4 justify-content-center px-lg-4 px-xl-2">
                <div class="col-md-4 col-lg-3 col-xl">
                    <div class="card card-value p-4 shadow-sm h-100">
                    <i class="bi bi-heart-fill display-5 mb-3"></i>
                    <h4 class="mb-2">LOYALTY</h4>
                    <p class="mb-0 text-muted">Faithful commitment to our members and shared goals.</p>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl">
                <div class="card card-value p-4 shadow-sm h-100">
                <i class="bi bi-shield-check display-5 mb-3"></i>
                <h4 class="mb-2">INTEGRITY</h4>
                <p class="mb-0 text-muted">Doing the right thing, even when no one is watching.</p>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-xl">
            <div class="card card-value p-4 shadow-sm h-100">
                <i class="bi bi-patch-check display-5 mb-3"></i>
                <h4 class="mb-2">HONESTY</h4>
                <p class="mb-0 text-muted">Transparency and truthfulness in every interaction.</p>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-xl">
            <div class="card card-value p-4 shadow-sm h-100">
                <i class="bi bi-eye display-5 mb-3"></i>
                <h4 class="mb-2">OPENNESS</h4>
                <p class="mb-0 text-muted">Promoting clear communication and accessibility.</p>
            </div>
        </div>

        <div class="col-md-4 col-lg-3 col-xl">
            <div class="card card-value p-4 shadow-sm h-100">
                <i class="bi bi-hand-thumbs-up-fill display-5 mb-3"></i>
                <h4 class="mb-2">KINDNESS</h4>
                <p class="mb-0 text-muted">Serving with compassion, respect, and a helpful spirit.</p>
            </div>
        </div>
    </div>
</div>
</section>

<section class="section-padding bg-white">
    <div class="container text-center">
        <h2 class="mb-5 fw-bold text-primary">Organizational Leadership</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <img src="assets/img/bod1.jpg" alt="Board of Directors" class="bod-img shadow" data-bs-toggle="modal" data-bs-target="#leadershipModal">
                <p class="mt-3 text-muted">Click the image to view the full Board and Committees gallery (6 Pages)</p>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="leadershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="leadershipCarousel" class="carousel slide" data-bs-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active"><img src="assets/img/bod1.jpg" class="d-block w-100"></div>
                        <div class="carousel-item"><img src="assets/img/bod2.jpg" class="d-block w-100"></div>
                        <div class="carousel-item"><img src="assets/img/bod3.jpg" class="d-block w-100"></div>
                        <div class="carousel-item"><img src="assets/img/bod4.jpg" class="d-block w-100"></div>
                        <div class="carousel-item"><img src="assets/img/bod5.jpg" class="d-block w-100"></div>
                        <div class="carousel-item"><img src="assets/img/bod6.jpg" class="d-block w-100"></div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#leadershipCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#leadershipCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>