<?php include_once 'db_function/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products & Services - Ozamiz City People's MPC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-blue: #002366;
            --primary-light: #0d6efd;
            --accent-gold: #ffc107;
            --bg-light: #f8f9fa;
            --text-dark: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-light);
        }

        /* Navbar Styling */
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
            background-color: var(--primary-light);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--primary-light) !important;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light) 100%);
            color: white;
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

        /* Section Styling */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 4px;
            background: var(--accent-gold);
            border-radius: 2px;
        }

        .section-padding {
            padding: 80px 0;
        }

        /* Service Card Styling */
        .service-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 5px solid var(--primary-light);
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-left-color: var(--accent-gold);
        }

        .service-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-blue) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: white;
        }

        .service-card h5 {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1.2rem;
        }

        .service-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        .service-card-tag {
            display: inline-block;
            background: var(--accent-gold);
            color: var(--primary-blue);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 15px;
        }

        /* Loan Services Section */
        .loan-services {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(255, 193, 7, 0.05) 100%);
        }

        /* Savings Products Section */
        .savings-products {
            background: linear-gradient(135deg, rgba(0, 35, 102, 0.05) 0%, rgba(13, 110, 253, 0.05) 100%);
        }

        /* Savings Card Special Styling */
        .savings-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 5px solid var(--accent-gold);
            height: 100%;
        }

        .savings-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-left-color: var(--primary-light);
        }

        .savings-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-gold) 0%, #ff9800 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: white;
        }

        .savings-card h5 {
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .savings-features {
            list-style: none;
            padding: 0;
        }

        .savings-features li {
            padding: 8px 0;
            color: #666;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            line-height: 1.5;
        }

        .savings-features li::before {
            content: '✓';
            display: inline-block;
            width: 20px;
            height: 20px;
            background: var(--accent-gold);
            color: var(--primary-blue);
            border-radius: 50%;
            text-align: center;
            margin-right: 10px;
            font-weight: bold;
            font-size: 0.85rem;
            line-height: 1.4;
            flex-shrink: 0;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-light) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-top: 60px;
        }

        .cta-section h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .btn-primary-custom {
            background: var(--accent-gold);
            color: var(--primary-blue);
            border: none;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary-custom:hover {
            background: white;
            color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .service-card,
            .savings-card {
                padding: 20px;
                margin-bottom: 20px;
            }

            .service-card-icon,
            .savings-card-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-card,
        .savings-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .service-card:nth-child(1) { animation-delay: 0.1s; }
        .service-card:nth-child(2) { animation-delay: 0.2s; }
        .service-card:nth-child(3) { animation-delay: 0.3s; }
        .service-card:nth-child(4) { animation-delay: 0.4s; }
        .service-card:nth-child(5) { animation-delay: 0.5s; }
        .service-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body>

<?php include_once 'includes/navbar.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1>Our Products & Services</h1>
        <p>Comprehensive Financial Solutions for Your Needs</p>
    </div>
</div>

<!-- Main Content -->
<div class="container my-5">

    <!-- LOAN SERVICES SECTION -->
    <section class="section-padding loan-services">
        <div class="container">
            <h2 class="section-title">🏦 Loan Services</h2>
            <p class="lead text-muted mb-5 text-center">
                We offer a variety of loan products tailored to meet your specific financial needs
            </p>

            <div class="row g-4">
                <!-- Salary Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h5>Salary Loan</h5>
                        <p>For individuals with regular salary income. Quick processing and competitive rates for salaried employees.</p>
                        <span class="service-card-tag">Quick Approval</span>
                    </div>
                </div>

                <!-- Pensioner Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h5>Pensioner Loan</h5>
                        <p>Designed specifically for retirees and pensioners. Flexible terms based on your pension income.</p>
                        <span class="service-card-tag">Special Terms</span>
                    </div>
                </div>

                <!-- Business/Commercial Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h5>Business/Commercial Loan</h5>
                        <p>For entrepreneurs and business owners. Support your business growth with our commercial lending options.</p>
                        <span class="service-card-tag">Flexible Terms</span>
                    </div>
                </div>

                <!-- Self-Employed Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5>Self-Employed Loan</h5>
                        <p>For skilled earners: drivers, mechanics, tailors, beauticians, and other professionals in various trades.</p>
                        <span class="service-card-tag">For Professionals</span>
                    </div>
                </div>

                <!-- Farmers Production Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <h5>Farmers Production Loan</h5>
                        <p>Supporting agriculture: lowland & upland farming, livestock production, and agricultural development.</p>
                        <span class="service-card-tag">Agricultural</span>
                    </div>
                </div>

                <!-- OFW/Overseas Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-airplane"></i>
                        </div>
                        <h5>OFW/Overseas Loan</h5>
                        <p>For overseas workers and seafarers. Convenient remittance-linked loans for Filipinos working abroad.</p>
                        <span class="service-card-tag">For OFWs</span>
                    </div>
                </div>

                <!-- Emergency Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <h5>Emergency Loan</h5>
                        <p>Quick access to funds for urgent and unforeseen financial needs. Fast disbursement available.</p>
                        <span class="service-card-tag">Quick Funds</span>
                    </div>
                </div>

                <!-- Professional Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <h5>Professional Loan</h5>
                        <p>For licensed professionals such as doctors, engineers, lawyers, and other registered practitioners.</p>
                        <span class="service-card-tag">For Professionals</span>
                    </div>
                </div>

                <!-- Fisherfolk's Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-water"></i>
                        </div>
                        <h5>Fisherfolk's Loan</h5>
                        <p>Supporting fishing communities. Loans for fishermen and fishing-related business operations.</p>
                        <span class="service-card-tag">Fishing Community</span>
                    </div>
                </div>

                <!-- Rice Loan -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-card-icon">
                            <i class="bi bi-bag"></i>
                        </div>
                        <h5>Rice Loan</h5>
                        <p>Specifically designed to support rice farmers and agricultural production cycles.</p>
                        <span class="service-card-tag">Agricultural</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SAVINGS PRODUCTS SECTION -->
    <section class="section-padding savings-products">
        <div class="container">
            <h2 class="section-title">💰 Savings Products</h2>
            <p class="lead text-muted mb-5 text-center">
                Grow your wealth with our secure and rewarding savings options
            </p>

            <div class="row g-4">
                <!-- Demand Deposit -->
                <div class="col-md-6 col-lg-6">
                    <div class="savings-card">
                        <div class="savings-card-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h5>Demand Deposit</h5>
                        <ul class="savings-features">
                            <li>Minimum Deposit: <strong>₱200.00</strong></li>
                            <li>Interest Compounded <strong>Quarterly</strong></li>
                            <li>Easy access to your funds</li>
                            <li>No withdrawal restrictions</li>
                        </ul>
                    </div>
                </div>

                <!-- Time Deposit -->
                <div class="col-md-6 col-lg-6">
                    <div class="savings-card">
                        <div class="savings-card-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h5>Time Deposit</h5>
                        <ul class="savings-features">
                            <li>Minimum Placement: <strong>6 Months</strong></li>
                            <li>Withdrawable Upon Maturity</li>
                            <li>Option to Roll Over</li>
                            <li>Rewarding Interest Rates</li>
                        </ul>
                    </div>
                </div>

                <!-- Share Capital & CBU Savings -->
                <div class="col-md-6 col-lg-6">
                    <div class="savings-card">
                        <div class="savings-card-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h5>Share Capital & CBU Savings</h5>
                        <ul class="savings-features">
                            <li>Savings For Life Program</li>
                            <li>Dividends Paid <strong>Annually</strong></li>
                            <li><strong>FREE Whole Life Savings Insurance</strong></li>
                            <li>Coverage: ₱15,000 - ₱300,000 CBU</li>
                        </ul>
                    </div>
                </div>

                <!-- Kiddie Saver -->
                <div class="col-md-6 col-lg-6">
                    <div class="savings-card">
                        <div class="savings-card-icon">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                        <h5>Kiddie Saver</h5>
                        <ul class="savings-features">
                            <li>Start Savings Habits Early</li>
                            <li>Opening: Minimum <strong>₱100.00</strong></li>
                            <li>Subsequent Deposits: Minimum <strong>₱50.00</strong></li>
                            <li>Interest Compounded <strong>Quarterly</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Get Started?</h2>
        <p>Choose the product or service that best fits your financial goals</p>
        <a href="index.php#contact" class="btn btn-primary-custom">Contact Us Today</a>
    </div>
</section>

<!-- Footer -->
<?php include_once 'includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Navbar scroll effect
    const navbar = document.querySelector('#mainNavbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

</body>
</html>
