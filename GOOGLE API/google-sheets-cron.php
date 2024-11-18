<?php
// =====
// Connect Google API Client for PHP without composer
// =====
require_once 'vendors/google-api/vendor/autoload.php';

// configure the Google Client
$client = new Google_Client();
$client->setApplicationName('Google Sheets API');
$client->setScopes([\Google_Service_Drive::DRIVE_METADATA]);
$client->setAccessType('offline');
// $client->setPrompt('select_account consent');
// credentials.json is the key file we downloaded while setting up our Google Sheets API
$path = 'vendors/google-api/credentials.json';
$client->setAuthConfig($path);

// configure the Drive Service
$service = new Google_Service_Drive($client);

// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
$fileId = '1OClGJhrwJbetD6nB3eOaQR187XfNwGLXutl8_pLVyqY';

// $files = $service->files->listFiles();
// foreach($files->getFiles() as $file) {
//   echo $file->getName();
//   echo ' - ';
//   echo $file->getId();
//   echo '<br>';
// }

// get latest time spreadsheet was modified on the Drive
$file = $service->files->get($fileId, ['fields' => 'name, id, modifiedTime']);
$modifiedTime = $file->getModifiedTime();
if ($modifiedTime === null) die('Error: File not found.');

// check if modification time changed from previous check
$filePath = $_SERVER['DOCUMENT_ROOT'] . '/lastModified.txt';
if (file_exists($filePath)) {
  // get modification time from previous check
  $lastModifiedTime = json_decode(file_get_contents($filePath));

  if ($modifiedTime === $lastModifiedTime) {
    // if spreadsheet wasn't modified
    die('Warning: No updates.');
  }
}

// file was modified or it's the first time
// save last modification time
file_put_contents($filePath, json_encode($modifiedTime));

// begin synchronization
include_once 'google-sheets-synchronization.php';
?>