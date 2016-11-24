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
        $response = $res->withStatus(200)->withHeader('Content-Type', 'application/json');

        $files = $req->getUploadedFiles();

        if (empty($files['name'])) {
          $this->logger->error('Expected a file under "name" key. ' . json_encode($files)));
          return $response->withStatus(400)->write(error('Expected a file under "name" key'));
        }

        $newfile = $files['name'];
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $dir = dirname($_SERVER["SCRIPT_FILENAME"]) . '/../upload/'.$uploadFileName;
            $newfile->moveTo($dir);
            return $response->write(json_encode(array('msg' => 'Upload successful. Filename: '.$uploadFileName)));
        }

        $this->logger->error('Error on upload: ' . $newfile->getError());
        return $response->withStatus(400)->write(error('Error on upload'));
    }
}
