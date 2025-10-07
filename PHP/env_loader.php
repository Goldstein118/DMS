
<?php
require_once __DIR__ . '/../Vendor/autoload.php';

use Dotenv\Dotenv;

// Point Dotenv to the base directory (where .env file is)
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
