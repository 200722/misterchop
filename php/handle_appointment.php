<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure this path points to Composer's autoload file

$appointmentsFile = 'appointments.json';

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'misterchop3@gmail.com'; // Replace with your Gmail address
        $mail->Password = ' tkzi nwwa xvvj xerq '; // Replace with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('misterchop3@gmail.com', 'Mister Chop’s Barber Shop');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'] ?? '';

    // Validate that the appointment date and time are in the future
    $appointmentDateTime = new DateTime("$date $time");
    $currentDateTime = new DateTime("now");

    if ($appointmentDateTime < $currentDateTime) {
        echo "<script>alert('Cannot book an appointment in the past.'); window.location.href='../html/book.html';</script>";
        exit;
    }
    // Load existing appointments
    $appointments = json_decode(file_get_contents($appointmentsFile), true) ?? [];

    // Check for an existing appointment at the same time
    $timeSlotTaken = false;
    foreach ($appointments as $appointment) {
        if ($appointment['date'] == $date && $appointment['time'] == $time) {
            // header('Location: slottaken.html');
            // exit;
            $timeSlotTaken = true;
            break;
        }
    }

    if ($timeSlotTaken) {
        // Generate available time slots for the same day
        $allSlots = [
            '10:00', '10:15', '10:30', '10:45',
            '11:00', '11:15', '11:30', '11:45',
            '12:00', '12:15', '12:30', '12:45',
            '13:00', '13:15', '13:30', '13:45',
            '14:00', '14:15', '14:30', '14:45',
            '15:00', '15:15', '15:30', '15:45',
            '16:00', '16:15', '16:30', '16:45',
            '17:00'
        ];

        $takenSlots = array_column(array_filter($appointments, function ($a) use ($date) {
            return $a['date'] === $date;
        }), 'time');
        $availableSlots = array_diff($allSlots, $takenSlots);

        // Store available slots in a session
        session_start();
        $_SESSION['availableSlots'] = $availableSlots;
        $_SESSION['chosenDate'] = $date;

        header('Location: slottaken.php');
        exit;
    } else {
        // Add new appointment with a unique ID
        $appointmentId = md5($date . $time . $email, $phone);
        $appointments[] = ['id' => $appointmentId, 'date' => $date, 'time' => $time, 'name' => $name, 'email' => $email, 'phone' => $phone, 'notes' => $notes];
        file_put_contents($appointmentsFile, json_encode($appointments));

        // Send confirmation email to client
        $cancelLink = "https://misterchop.nl/php/cancel_appointment.php?id=$appointmentId"; // Replace with your actual URL
        $message = "Beste $name,<br><br>" .
            "Bedankt voor het boeken van een afspraak bij MisterChop! We zijn verheugd om je binnenkort te mogen verwelkomen in onze salon, waar we je een onvergetelijke ervaring beloven.<br><br>" .
            "Bij MisterChop streven we ernaar om je op je gemak te stellen en ervoor te zorgen dat je met een tevreden en stijlvolle look de salon verlaat. Mocht je voorafgaand aan je afspraak vragen of specifieke wensen hebben, aarzel dan niet om contact met ons op te nemen.<br><br>" .
            "Je afspraak staat gepland op <strong>$date</strong> om <strong>$time</strong>. Mocht je onverhoopt verhinderd zijn, dan kun je de afspraak eenvoudig annuleren door op <a href='$cancelLink'>deze link</a> te klikken.<br><br>" .
            "Voor meer informatie of bij vragen kun je ons bereiken op:<br>" .
            "Telefoonnummer Misterchop: 0306330569<br><br>" .
            "We kijken uit naar je bezoek.<br><br>" .
            (!empty($notes) ? "Opmerkingen: <strong>" . htmlspecialchars($notes) . "</strong><br><br>" : "") .
            "Met vriendelijke groet,<br>" .
            "Het Team van MisterChop!";


        sendEmail($email, "Appointment Confirmation", $message);

        // Send email to business
        sendEmail(
            'misterchop3@gmail.com',
            "New Appointment Booked",
            "$name Heeft net afspraak gemaakt.<br>" .
                "Afspraakdetails: $date <br> At $time.<br>" .
                "Klantgegevens:<br>Naam: $name,<br>" .
                "Email: $email,<br>" .
                "Opmerkingen (optioneel): $notes.<br>" .
                "Cancel-link: $cancelLink"

        );

        // Redirect to thank you page
        header('Location: ../html/thankyou.html?date=' . urlencode($date) . '&time=' . urlencode($time));
        exit;
    }
}
