<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure this path points to Composer's autoload file

$appointmentsFile = 'appointments.json';

function sendCancellationEmail($to, $clientName, $date, $time)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'misterchop3@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'tkzi nwwa xvvj xerq'; // Replace with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('misterchop3@gmail.com', 'Mister Chop’s Barber Shop');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Appointment Cancellation';
        $mail->Body    = "De volgende afspraak is geannuleerd voor meer informatie graag ons bellen  0684504925: <br>Client: $clientName<br>Date: $date<br>Time: $time";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // Load existing appointments
    $appointments = json_decode(file_get_contents($appointmentsFile), true);

    // Check if the appointment exists and cancel it
    foreach ($appointments as $index => $appointment) {
        if ($appointment['id'] == $appointmentId) {
            // Capture appointment details before cancellation
            $clientName = $appointment['name'];
            $clientEmail = $appointment['email'];
            $date = $appointment['date'];
            $time = $appointment['time'];

            // Cancel the appointment
            unset($appointments[$index]);
            file_put_contents($appointmentsFile, json_encode(array_values($appointments)));

            // Send cancellation email to business
            sendCancellationEmail('misterchop3@gmail.com', $clientName, $date, $time); // send to business email
            sendCancellationEmail($clientEmail, $clientName, $date, $time); // Send to client


            echo "Appointment canceled successfully.";
            exit;
        }
    }

    echo "Appointment not found.";
} else {
    echo "No appointment ID provided.";
}
