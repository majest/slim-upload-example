<?php

namespace App\Action;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

function error($msg)
{
    return json_encode(['error' => true, 'msg' => $msg]);
}

final class UploadAction
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $req, Response $res, $args)
    {
        $this->logger->info('Upload action dispatched');
        //$response = $res->withStatus(200)->withHeader('Content-Type', 'application/json');

        $files = $req->getUploadedFiles();

        if (empty($files['newfile'])) {
            throw new \Exception('Expected a newfile');
        }

        $newfile = $files['newfile'];
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $newfile->moveTo('/tmp/data/'.$uploadFileName);
            return $response->write(json_encode(array('msg' => 'Upload successful. Filename: '.$uploadFileName)));
        }

        return $response->withStatus(400)->write(error('Error on upload'));
    }
}
