<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload.php file
require './PHPMailer/Exception.php';
require './PHPMailer/PHPMailer.php';
require './PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $prefix = $_POST['prefix'];
    $phoneNumber = $_POST['phoneNumber'];
    $company = $_POST['company'];
    $country = $_POST['country'];

    // Save data to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "kpi";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO info_table (firstName, lastName, email, prefix, phoneNumber, company, country)
    VALUES ('$firstName', '$lastName', '$email', '$prefix', '$phoneNumber', '$company', '$country')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

    // Send email
    sendSupportMail($firstName, $lastName, $email, $prefix, $phoneNumber, $company, $country);

    // Download file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="TKI-Membership-Brochure.pdf"');
    readfile('TKI-Membership-Brochure.pdf');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function sendSupportMail($firstName, $lastName, $email, $prefix, $phoneNumber, $company, $country)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alaa.aahmeedd@gmail.com'; // Replace with your email address
        $mail->Password = 'zplt asbs umcw hojr'; // Replace with your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('alaa.aahmeedd@gmail.com', 'Alaa'); // Replace with your email address and name
        $mail->addAddress('alex.podariu@kpiinstitute.com', 'alex'); // Replace with ...

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'New form submission from Alaa';
        $mail->Body = '<h1>Hello Contact Form:-</h1><br>' .
            'First Name: ' . $firstName . '<br>' .
            'Last Name: ' . $lastName . '<br>' .
            'Email: ' . $email . '<br>' .
            'Prefix: ' . $prefix . '<br>' .
            'Phone Number: ' . $phoneNumber . '<br>' .
            'Company: ' . $company . '<br>' .
            'Country: ' . $country;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        return false;
    }
    return false;
}
