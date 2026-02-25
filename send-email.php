<?php
// Email configuration
$receiver_email = 'cmdharshinii@gmail.com';

// Set headers to return JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Contact Form Submission';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validation
    $errors = [];
    
    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email address is required';
    }
    
    if (empty($message) || strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
    
    // Sanitize inputs to prevent header injection
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($subject ?: 'Contact Form Submission', ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    // Prepare email
    $to = $receiver_email;
    $email_subject = 'New Contact Form Submission: ' . $subject;
    
    // Email headers
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Email body HTML
    $body = "<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 5px; }
            .header { background-color: #FFB800; padding: 15px; border-radius: 5px 5px 0 0; color: white; }
            .content { background-color: white; padding: 20px; border: 1px solid #ddd; }
            .field { margin-bottom: 15px; }
            .field-label { font-weight: bold; color: #FFB800; }
            .field-value { margin-top: 5px; color: #555; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #999; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='field-label'>From:</div>
                    <div class='field-value'>" . $name . " (" . $email . ")</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Subject:</div>
                    <div class='field-value'>" . $subject . "</div>
                </div>
                <div class='field'>
                    <div class='field-label'>Message:</div>
                    <div class='field-value'>" . nl2br($message) . "</div>
                </div>
            </div>
            <div class='footer'>
                <p>This email was sent from your website's contact form.</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Send email
    if (mail($to, $email_subject, $body, $headers)) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Your message has been sent successfully. We will get back to you soon!'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'errors' => ['Failed to send email. Please try again later.']
        ]);
    }
    
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'errors' => ['Invalid request method. Only POST is allowed.']
    ]);
}
?>
