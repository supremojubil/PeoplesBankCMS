<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // FORM DATA
    // =========================
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $branch   = trim($_POST['branch'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    // =========================
    // VALIDATION
    // =========================
    $errors = [];

    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email address is required.";
    }

    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (!empty($errors)) {
        $_SESSION['contact_errors'] = $errors;
        header('Location: ../contact.php');
        exit;
    }

    // =========================
    // ADMIN EMAIL CONTENT
    // =========================
    $to = 'ocpcozamiz@gmail.com';

    $subject = 'New Contact Inquiry from ' . $fullname;

    $emailBody = "
    <html>
    <body style='font-family:Arial;color:#333'>
        <h2>New Contact Inquiry</h2>

        <p><strong>Name:</strong> $fullname</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> " . ($phone ?: 'N/A') . "</p>
        <p><strong>Branch:</strong> $branch</p>

        <p><strong>Message:</strong></p>
        <div style='background:#f4f4f4;padding:10px;border-left:4px solid #0d6efd;'>
            " . nl2br(htmlspecialchars($message)) . "
        </div>
    </body>
    </html>
    ";

    // =========================
    // SMTP CONFIG (SINGLE ACCOUNT)
    // =========================
    //TESTING EMAIL ACCOUNT - REPLACE WITH YOUR OWN CREDENTIALS
    $smtpUser = 'ocpcozamiz@gmail.com';
    $smtpPass = 'qxkrevwhxsahfhki'; // Use an app password if 2FA is enabled

    try {

        // =========================
        // 1. ADMIN EMAIL
        // =========================
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($smtpUser, 'Website Contact');
        $mail->addAddress($to);
        $mail->addReplyTo($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $emailBody;

        $mail->send();


        // =========================
        // 2. CUSTOMER AUTO-REPLY (BANK STYLE)
        // =========================
        $mail2 = new PHPMailer(true);

        $mail2->isSMTP();
        $mail2->Host = 'smtp.gmail.com';
        $mail2->SMTPAuth = true;
        $mail2->Username = $smtpUser;
        $mail2->Password = $smtpPass;
        $mail2->SMTPSecure = 'tls';
        $mail2->Port = 587;

        $mail2->setFrom($smtpUser, 'Ozamiz City People\'s MPC');
        $mail2->addAddress($email);

        $mail2->isHTML(true);
        $mail2->Subject = "Inquiry Received - Ozamiz City People's MPC";

        $mail2->Body = "
<html>
<body style='margin:0;padding:0;background:#f5f7fb;font-family:Arial, sans-serif;'>

<div style='max-width:600px;margin:30px auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e6e9ef;'>

    <!-- HEADER -->
    <div style='background:#002366;color:#ffffff;padding:20px;text-align:center;'>
        <h2 style='margin:0;'>Ozamiz City People's MPC</h2>
        <p style='margin:5px 0 0;font-size:13px;'>Official Customer Support Confirmation</p>
    </div>

    <!-- BODY -->
    <div style='padding:25px;color:#333;'>

        <p style='font-size:16px;'>Dear <strong>" . htmlspecialchars($fullname) . "</strong>,</p>

        <p style='line-height:1.6;'>
            Thank you for contacting <strong>Ozamiz City People's Multi-Purpose Cooperative</strong>.
            We have successfully received your inquiry and it is now being reviewed by our support team.
        </p>

        <!-- REFERENCE BOX -->
        <div style='background:#f0f4ff;border-left:4px solid #0d6efd;padding:12px;margin:20px 0;border-radius:5px;'>
            <strong>Reference Details</strong><br><br>
            <b>Name:</b> " . htmlspecialchars($fullname) . "<br>
            <b>Email:</b> " . htmlspecialchars($email) . "<br>
            <b>Branch:</b> " . htmlspecialchars($branch) . "<br>
        </div>

        <!-- MESSAGE -->
        <p><strong>Your Message:</strong></p>
        <div style='background:#fafafa;border:1px solid #eee;padding:12px;border-radius:5px;white-space:pre-wrap;'>
            " . nl2br(htmlspecialchars($message)) . "
        </div>

        <!-- NOTICE -->
        <div style='margin-top:25px;padding:15px;background:#fff8e5;border-left:4px solid #f0ad4e;border-radius:5px;'>
            <strong>Important Notice:</strong><br>
            Our team will respond within <b>24–48 business hours</b>.
            Please keep this email for your reference.
        </div>

        <p style='margin-top:25px;line-height:1.6;'>
            If you need urgent assistance, you may reply directly to this email.
        </p>

        <p>
            Sincerely,<br>
            <strong>Customer Support Team</strong><br>
            Ozamiz City People's MPC
        </p>

    </div>

    <!-- FOOTER -->
    <div style='background:#002366;color:#fff;text-align:center;padding:15px;font-size:12px;'>
        © " . date('Y') . " Ozamiz City People's Multi-Purpose Cooperative. All rights reserved.
    </div>

</div>

</body>
</html>
";

        $mail2->send();


        // =========================
        // SUCCESS
        // =========================
        $_SESSION['contact_success'] = "Thank you! Your inquiry has been sent successfully.";

    } catch (Exception $e) {
        $_SESSION['contact_error'] = "Mailer Error: " . $mail->ErrorInfo;
    }

    header('Location: ../contact.php');
    exit;
}
?>