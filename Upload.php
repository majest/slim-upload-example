<?php
namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class Upload
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $req, Response $res, $args)
    {
        $this->logger->info("Upload action dispatched");

        $data = $req->getParsedBody();
        $response = $res->withStatus(200)->withHeader('Content-Type', 'application/json');

        if (!isset($_FILES['name'])) {
            return $response->withStatus(400)->write(error('No file uploaded'));
        }

        $file = $_FILES['name'];
        if ($file['error'] === 0 && isset($file['name']) && move_uploaded_file($file['tmp_name'], '/tmp/data/' . $file['name']) === true) {
            return $response->write(json_encode(array("msg" => "Upload successful. Filename: " . $file['tmp_name'])));
        }

        return $response->withStatus(400)->write(error('Error on upload'));
    }
}
