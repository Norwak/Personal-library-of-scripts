<?php
// =====
// CONNECT TO A DATABASE
// =====
require_once 'wedadmin/config.inc.php';

$host = $CFG['db_hostname'];
$db   = $CFG['db_basename'];
$user = $CFG['db_username'];
$pass = $CFG['db_password'];
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}



// =====
// Connect Google API Client for PHP without composer
// =====
require_once 'vendors/google-api/vendor/autoload.php';

// configure the Google Client
$client = new \Google_Client();
$client->setApplicationName('Google Sheets API');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
// credentials.json is the key file we downloaded while setting up our Google Sheets API
$path = 'vendors/google-api/credentials.json';
$client->setAuthConfig($path);

// configure the Sheets Service
$service = new \Google_Service_Sheets($client);

// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
$spreadsheetId = '1OClGJhrwJbetD6nB3eOaQR187XfNwGLXutl8_pLVyqY';



function gss_process_floats($input) {
  $input = preg_replace('/\s+/', '', $input); // remove whitespace
  $input = str_replace(',', '.', $input); // correct decimal separator
  if (is_numeric($input)) {
    return (float) $input;
  } else {
    return null;
  }
}



// =====
// GET PRODUCTS AND THEIR DATA
// =====

// Fetch the rows
$range = 'Список товаров';
try {
  $response = $service->spreadsheets_values->get($spreadsheetId, $range);
} catch (Google\Service\Exception $th) {}

if (is_object($response)) {
  $values = $response->getValues();

  // Remove row with headers
  array_shift($values);

  // clear table
  $stmt = $pdo->prepare("TRUNCATE TABLE wed_calculator");
  $stmt->execute();

  // insert data into table
  if (count($values) > 0) {
    foreach ($values as $row) {
      if ($row[0] && is_string($row[0])) {
        $product = trim($row[0]);

        $gost = trim($row[1]);

        $density = gss_process_floats($row[2]);

        $in_ton = gss_process_floats($row[3]);

        $in_meter3 = gss_process_floats($row[4]);

        $price_tonne = gss_process_floats($row[5]);

        $price_meter3 = gss_process_floats($row[6]);

        $stmt = $pdo->prepare("INSERT INTO wed_calculator (title, img, gost, density, in_ton, in_meter3, price_tonne, price_meter3) VALUES (?, '', ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product, $gost, $density, $in_ton, $in_meter3, $price_tonne, $price_meter3]);
      }
    }
  }
} else {
  echo "Error: Spreadsheet accessed, but the required list wasn't found.";
}



// =====
// GET DELIVERY PRICES
// =====

// Fetch the rows
$range = 'Цены на доставку';
try {
  $response = $service->spreadsheets_values->get($spreadsheetId, $range);
} catch (Google\Service\Exception $th) {}

if (is_object($response)) {
  $values = $response->getValues();

  // Remove row with headers
  array_shift($values);

  // clear table
  $stmt = $pdo->prepare("TRUNCATE TABLE wed_deliveryprices");
  $stmt->execute();

  // insert data into table
  if (count($values) > 0) {
    foreach ($values as $row) {
      if ($row[0] && is_string($row[0])) {
        $city = trim($row[0]);

        $price_to10 = gss_process_floats($row[1]);

        $price_to20 = gss_process_floats($row[2]);

        $stmt = $pdo->prepare("INSERT INTO wed_deliveryprices (title, img, price_to10, price_to20) VALUES (?, '', ?, ?)");
        $stmt->execute([$city, $price_to10, $price_to20]);
      }
    }
  }
  echo 'Success: Done.';
} else {
  echo "Error: Spreadsheet accessed, but the required list wasn't found.";
}
?>