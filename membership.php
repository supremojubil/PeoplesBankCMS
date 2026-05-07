<?php
include_once 'db_function/db.php';

// Fetch active member stories
$stmt = $pdo->prepare("SELECT * FROM member_stories WHERE status = 'active' ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$member_stories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership - Ozamiz City People's MPC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <style>
        :root {
            --primary: #002366;
            --accent: #ffc107;
            --surface: #f8f9fa;
            --text: #343a40;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--surface);
            color: var(--text);
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

        .hero-membership {
            position: relative;
            background: linear-gradient(135deg, rgba(0,35,102,0.9), rgba(13,110,253,0.9)), url('assets/img/member.jpg') center/cover no-repeat;
            min-height: 520px;
            display: flex;
            align-items: center;
            color: white;
        }

        .hero-membership::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.28);
        }

        .hero-membership .hero-content {
            position: relative;
            z-index: 1;
            max-width: 640px;
        }

        .hero-membership h1 {
            font-size: clamp(2.8rem, 4vw, 4.2rem);
            font-weight: 800;
            line-height: 1.02;
            margin-bottom: 1rem;
        }

        .hero-membership p {
            font-size: 1.1rem;
            max-width: 540px;
            margin-bottom: 1.75rem;
            opacity: 0.92;
        }

        .btn-join {
            background: var(--accent);
            color: var(--primary);
            border: none;
            padding: 14px 28px;
            font-weight: 700;
            border-radius: 50px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .btn-join:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.18);
        }

        .section {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--primary);
        }

        .section-subtitle {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 2.5rem;
        }

        .feature-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 18px 45px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 50px rgba(0,0,0,0.12);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: rgba(255,193,7,0.15);
            color: var(--accent);
            margin-bottom: 18px;
            font-size: 1.55rem;
        }

        .feature-card h5 {
            font-size: 1.15rem;
            margin-bottom: 16px;
            color: var(--primary);
        }

        .feature-card p {
            color: #5e6472;
            line-height: 1.8;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .image-grid img {
            width: 100%;
            border-radius: 24px;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease, filter 0.35s ease;
        }

        .image-grid img:hover {
            transform: scale(1.03);
            filter: brightness(1.02);
        }

        .process-step {
            display: flex;
            gap: 18px;
            align-items: flex-start;
            background: white;
            border-radius: 20px;
            padding: 26px;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow: 0 12px 28px rgba(0,0,0,0.06);
            transition: transform 0.3s ease;
        }

        .process-step:hover {
            transform: translateY(-4px);
        }

        .step-number {
            min-width: 56px;
            min-height: 56px;
            border-radius: 16px;
            background: var(--primary);
            color: white;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .step-content h6 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .step-content p {
            color: #6c757d;
            line-height: 1.7;
            margin: 0;
        }

        .testimonial-card {
            background: linear-gradient(135deg, rgba(0,35,102,0.1), rgba(255,193,7,0.12));
            border-radius: 24px;
            padding: 32px;
            border: none;
        }

        .testimonial-card p {
            font-size: 1rem;
            color: #343a40;
            line-height: 1.9;
            margin-bottom: 24px;
        }

        .testimonial-author {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .testimonial-author img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 18px;
            border: 3px solid white;
        }

        .testimonial-author div span {
            display: block;
            font-size: 0.95rem;
            color: #6c757d;
        }

        .testimonial-author div strong {
            display: block;
            font-size: 1.05rem;
            color: var(--primary);
        }

        footer {
            background: #002366;
            color: #ffffff;
            padding: 40px 0;
        }

        .footer-note {
            color: rgba(255,255,255,0.72);
            margin-top: 18px;
        }

        @media (max-width: 992px) {
            .image-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .hero-membership {
                min-height: 420px;
                padding: 60px 0;
            }

            .image-grid {
                grid-template-columns: 1fr;
            }

            .process-step {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<?php include_once 'includes/navbar.php'; ?>

<section class="hero-membership">
    <div class="container">
        <div class="hero-content">
            <span class="badge bg-warning text-primary mb-3">Become a Member</span>
            <h1>Join Our Cooperative Community</h1>
            <p>Experience the benefits of secure savings, loan support, and a strong member network that cares for your future.</p>
            <a href="#join" class="btn btn-join">How to Join</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Membership Benefits</h2>
            <p class="section-subtitle">Empowering every member with financial access, trust, and growth.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-people-fill"></i></div>
                    <h5>Community Support</h5>
                    <p>Be part of a cooperative that supports local families, businesses, and livelihoods in Ozamiz City.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-cash-stack"></i></div>
                    <h5>Financial Security</h5>
                    <p>Grow your savings safely while earning dividends and enjoying flexible deposit options.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h5>Preferential Loans</h5>
                    <p>Access lower rates and priority loan support for members, including salary, business, and emergency loans.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Member Stories</h2>
            <p class="section-subtitle">A glimpse of our active members and the positive impact of membership.</p>
        </div>

        <div class="image-grid">
            <?php if (count($member_stories) > 0): ?>
                <?php foreach ($member_stories as $story): ?>
                    <div class="testimonial-card">
                        <p>"<?php echo htmlspecialchars($story['story']); ?>"</p>
                        <div class="testimonial-author">
                            <div>
                                <strong><?php echo htmlspecialchars($story['name']); ?></strong>
                                <span><?php echo htmlspecialchars($story['position'] ?? 'Member'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="testimonial-card">
                        <p>"Joining the cooperative gave me peace of mind and helped me grow my savings while also gaining access to affordable loan options for my business."</p>
                        <div class="testimonial-author">
                            <div>
                                <strong>Maria Dela Cruz</strong>
                                <span>Small Business Owner</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section" id="join">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <h2 class="section-title">How to Join</h2>
                <p class="section-subtitle">Simple steps to become a registered member and enjoy full cooperative privileges.</p>

                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h6>Prepare Requirements</h6>
                        <p>Bring a valid ID, proof of address, and your initial savings deposit to our office.</p>
                    </div>
                </div>
                <div class="process-step mt-4">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h6>Submit Application</h6>
                        <p>Complete the membership form, review the cooperative policies, and submit your documents.</p>
                    </div>
                </div>
                <div class="process-step mt-4">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h6>Start Enjoying Benefits</h6>
                        <p>Once approved, you can access member-only savings, loans, dividends, and community programs.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="testimonial-card">
                    <?php if (count($member_stories) > 0): ?>
                        <p>"<?php echo htmlspecialchars($member_stories[0]['story']); ?>"</p>
                        <div class="testimonial-author">
                            <div>
                                <strong><?php echo htmlspecialchars($member_stories[0]['name']); ?></strong>
                                <span><?php echo htmlspecialchars($member_stories[0]['position'] ?? 'Member'); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>"Joining the cooperative gave me peace of mind and helped me grow my savings while also gaining access to affordable loan options for my business."</p>
                        <div class="testimonial-author">
                            <img src="assets/img/member1.jpg" alt="Member testimonial">
                            <div>
                                <strong>Maria Dela Cruz</strong>
                                <span>Small Business Owner</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container text-center">
        <p class="mb-0">Ozamiz City People's MPC</p>
        <p class="footer-note">Public Mall Drive, Ozamiz City | (088) 521-0296 | ocpcozamiz@gmail.com</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
