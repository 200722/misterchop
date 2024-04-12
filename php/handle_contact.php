<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $file = $_FILES['cv'];

    // Check for file upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo 'Error during file upload: ' . $file['error'];
        exit;
    }

    // Validate file type
    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo 'Invalid file format.';
        exit;
    }

    // Ensure the upload directory exists
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate a unique file name to avoid overwriting existing files
    $uploadPath = $uploadDir . uniqid() . '_' . basename($file['name']);

    // Move the uploaded file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo 'Failed to move uploaded file.';
        exit;
    }
    // Prepare and send email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'misterchop3@gmail.com';
        $mail->Password = 'tkzi nwwa xvvj xerq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('misterchop3@gmail.com', 'Mister Chop’s Barber Shop');
        $mail->addAddress('misterchop3@gmail.com'); // Add your email address

        // Attach CV
        $mail->addAttachment($uploadPath);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact from ' . $name;
        $mail->Body    = 'You have received a new message from ' . $name . '<br>Email: ' . $email;

        $mail->send();
        echo 'Message has been sent';
        header('Location: ../html/tank.html');
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Redirect back to the form if the page is accessed directly
    header('Location: ../html/contact.html');
    exit;
}
?>
