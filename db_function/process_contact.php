<?php
session_start();

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
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

    // Prepare email content
    $to = 'namuagjubil30@gmail.com'; // Change to your email
    $subject = 'New Contact Inquiry from ' . htmlspecialchars($fullname);
    
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #002366; color: white; padding: 15px; border-radius: 5px 5px 0 0; }
            .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
            .field { margin-bottom: 15px; }
            .field-label { font-weight: bold; color: #002366; }
            .field-value { color: #555; }
            .footer { background: #002366; color: white; padding: 10px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class=\"container\">
            <div class=\"header\">
                <h2>New Contact Inquiry</h2>
            </div>
            <div class=\"content\">
                <div class=\"field\">
                    <span class=\"field-label\">Name:</span>
                    <div class=\"field-value\">" . htmlspecialchars($fullname) . "</div>
                </div>
                <div class=\"field\">
                    <span class=\"field-label\">Email:</span>
                    <div class=\"field-value\"><a href=\"mailto:" . htmlspecialchars($email) . "\">" . htmlspecialchars($email) . "</a></div>
                </div>
                <div class=\"field\">
                    <span class=\"field-label\">Phone:</span>
                    <div class=\"field-value\">" . (empty($phone) ? 'N/A' : htmlspecialchars($phone)) . "</div>
                </div>
                <div class=\"field\">
                    <span class=\"field-label\">Branch Inquiry:</span>
                    <div class=\"field-value\">" . htmlspecialchars($branch) . "</div>
                </div>
                <div class=\"field\">
                    <span class=\"field-label\">Message:</span>
                    <div class=\"field-value\" style=\"background: white; padding: 10px; border-left: 3px solid #0d6efd; white-space: pre-wrap;\">" . htmlspecialchars($message) . "</div>
                </div>
            </div>
            <div class=\"footer\">
                <p>Sent from Ozamiz City People's Multi-Purpose Cooperative Website</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . htmlspecialchars($email) . "\r\n";
    $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";

    // Send email
    if (mail($to, $subject, $emailBody, $headers)) {
        $_SESSION['contact_success'] = "Thank you! Your inquiry has been sent successfully. We'll get back to you soon.";
        
        // Optional: Send confirmation email to user
        $userSubject = "We Received Your Inquiry - Ozamiz City People's MPC";
        $userBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #002366; color: white; padding: 15px; border-radius: 5px 5px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .footer { background: #002366; color: white; padding: 10px; text-align: center; border-radius: 0 0 5px 5px; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class=\"container\">
                <div class=\"header\">
                    <h2>Inquiry Confirmation</h2>
                </div>
                <div class=\"content\">
                    <p>Hi " . htmlspecialchars($fullname) . ",</p>
                    <p>Thank you for contacting Ozamiz City People's Multi-Purpose Cooperative. We have received your inquiry and will respond to you within 24-48 business hours.</p>
                    <p><strong>Your Reference Message:</strong></p>
                    <p style=\"background: white; padding: 10px; border-left: 3px solid #0d6efd; white-space: pre-wrap;\">" . htmlspecialchars($message) . "</p>
                    <p>Best regards,<br>Ozamiz City People's MPC Team</p>
                </div>
                <div class=\"footer\">
                    <p>Contact us: ocpcozamiz@gmail.com | (088) 521-0296</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $userHeaders = "MIME-Version: 1.0" . "\r\n";
        $userHeaders .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $userHeaders .= "From: ocpcozamiz@gmail.com\r\n";
        
        mail($email, $userSubject, $userBody, $userHeaders);
    } else {
        $_SESSION['contact_error'] = "Failed to send inquiry. Please try again later.";
    }

    header('Location: ../contact.php');
    exit;
}
?>
