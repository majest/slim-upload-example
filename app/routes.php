<?php
// Routes

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->post('/upload', App\Action\UploadAction::class)
    ->setName('upload');
