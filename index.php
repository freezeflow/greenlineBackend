<?php
require __DIR__ . '/vendor/autoload.php';

$dontenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__.'/greenline-backend'));
$dontenv->load();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
set_time_limit(300);

use App\sendData\sendEmail,
    Symfony\Component\Mailer\Transport,
    Symfony\Component\Mailer\Exception\TransportExceptionInterface,
    Symfony\Component\Mailer\Mailer;

try {
    $dsn = 'smtp://'.$_ENV['HOST_EMAIL'].':'.$_ENV['PASS'].':587';

    $transport = Transport::fromDsn($dsn);//Add app password if 2 step verification is enabled
    $mailer = new Mailer($transport);

    $sendEmail = new sendEmail();

    $sendEmail->sendEmail($mailer);

    http_response_code(200);
    echo json_encode([
        'status' => 'success', 
        'message' => 'Email sent successfully'
    ]);

} catch (TransportExceptionInterface $e) {
    // Handle mailer-specific exceptions
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while sending the email.',
        'message' => $e->getMessage(),
    ]);
}catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while processing your request.',
        'message' => $e->getMessage(),
    ]);
}
