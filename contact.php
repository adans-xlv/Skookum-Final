<?php
if (!$_POST) exit;

// Email address verification
function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

// Sanitize input data
$name     = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email    = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone    = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$subject  = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);

// Form validation
if (empty($name)) {
    echo '<div class="error_message">You must enter your name.</div>';
    exit();
} elseif (empty($email)) {
    echo '<div class="error_message">Please enter a valid email address.</div>';
    exit();
} elseif (empty($phone)) {
    echo '<div class="error_message">Please enter a valid phone number.</div>';
    exit();
} elseif (!preg_match('/^[0-9]+$/', $phone)) {
    echo '<div class="error_message">Phone number can only contain digits.</div>';
    exit();
} elseif (!isEmail($email)) {
    echo '<div class="error_message">You have entered an invalid e-mail address, try again.</div>';
    exit();
} elseif (empty($comments)) {
    echo '<div class="error_message">Please enter your message.</div>';
    exit();
}

// Configuration option
// Enter the email address that you want emails to be sent to
$address = "opoti63@gmail.com";

// Email subject
$e_subject = 'You\'ve been contacted by ' . $name . '.';

// Compose the email content
$e_body = "You have been contacted by $name with regards to $subject, their additional message is as follows." . PHP_EOL . PHP_EOL;
$e_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
$e_reply = "You can contact $name via email, $email or via phone $phone";

$msg = wordwrap($e_body . $e_content . $e_reply, 70);

// Email headers
$headers = "From: $email" . PHP_EOL;
$headers .= "Reply-To: $email" . PHP_EOL;
$headers .= "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

// Send email
if (mail($address, $e_subject, $msg, $headers)) {
    echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h4 class='highlight'>Thank you <strong>$name</strong>, your message has been submitted to us.</h4>";
    echo "</div>";
    echo "</fieldset>";
} else {
    echo 'ERROR!';
}