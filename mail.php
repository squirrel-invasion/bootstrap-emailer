<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// require ReCaptcha class
require('assets/recaptcha-master/src/autoload.php');

// configure
$from = 'Raigosa Translation Services | <no-reply@raigosatranslationservices.com>';
$sendTo = 'eric@webspeakmedia.com';
$subject = 'New contact form submission';
$fields = array('your_firstname' => 'First Name', 'your_lastname' => 'Last Name', 'your_phone' => 'Phone', 'your_mail' => 'Email', 'your_address' =>'Mailing Address', 'your_message' => 'Comments');
$selectedServices  = 'None';
  if(isset($_POST['services']) && is_array($_POST['services']) && count($_POST['services']) > 0){
      $selectedServices = implode(', ', $_POST['services']);
  }
$okMessage = 'Contact form successfully submitted. Thank you, we will get back to you soon!';
$errorMessage = 'There was an error while submitting the form. Please fill out the form again make sure you have checked the box is checked proving you are not a Robot.';
$recaptchaSecret = '6LcCn0MUAAAAANE50kJZSXEwn1XCBpGLKB0VwaZ3';

// let's do the sending

try
{
    if (!empty($_POST)) {

        // validate the ReCaptcha, if something is wrong, we throw an Exception,
        // i.e. code stops executing and goes to catch() block

        if (!isset($_POST['g-recaptcha-response'])) {
            throw new \Exception('ReCaptcha is not set.');
        }

        // do not forget to enter your secret key in the config above
                $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret, new \ReCaptcha\RequestMethod\CurlPost());

        // we validate the ReCaptcha field together with the user's IP address

        $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);


        if (!$response->isSuccess()) {
            throw new \Exception('ReCaptcha was not validated.');
        }

        // everything went well, we can compose the message, as usually

        $emailText = "You have new message from Raigosa Translation Services contact form\n=============================\n";

        foreach ($_POST as $key => $value) {

            if (isset($fields[$key])) {
                $emailText .= "$fields[$key]: $value\n";
                $emailText .= 'Selected Projects: ' . $selectedProjects;
            }
        }

        $headers = array('Content-Type: text/plain; charset="UTF-8";',
            'From: ' . $from,
            'Reply-To: ' . $from,
            'Return-Path: ' . $from,
        );

        mail($sendTo, $subject, $emailText, implode("\n", $headers));

        $responseArray = array('type' => 'success', 'message' => $okMessage);
    }
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
else {
    echo $responseArray['message'];
}
