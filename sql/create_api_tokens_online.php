<?php
// Run this script ONCE on the online server to create the api_tokens table.
// Upload to the web root and visit in your browser, then DELETE this file.

// CodeIgniter config files start with a `defined('BASEPATH') OR exit(...)`
// guard, so BASEPATH has to exist before the require or this script quietly
// exits with "No direct script access allowed" and creates nothing.
// database.php also reads ENVIRONMENT for its db_debug flag.
define('BASEPATH', __DIR__);
defined('ENVIRONMENT') OR define('ENVIRONMENT', 'production');

require_once __DIR__ . '/application/config/database.php';

header('Content-Type: text/plain; charset=utf-8');

$conn = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

echo "Connected to database: {$db['default']['database']}\n";

$sql = "CREATE TABLE IF NOT EXISTS api_tokens (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_key      VARCHAR(100) NOT NULL,
  position      VARCHAR(45)  NOT NULL,
  token_hash    CHAR(40)     NOT NULL,
  device_id     VARCHAR(120) NULL DEFAULT NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_seen_at  DATETIME     NULL DEFAULT NULL,
  expires_at    DATETIME     NULL DEFAULT NULL,
  PRIMARY KEY (id),
  KEY api_tokens_user (user_key),
  KEY api_tokens_hash (token_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) !== TRUE) {
    die('Error creating table: ' . $conn->error);
}

// Prove it actually exists rather than trusting CREATE ... IF NOT EXISTS.
$check = $conn->query("SHOW TABLES LIKE 'api_tokens'");
if ($check && $check->num_rows > 0) {
    $count = $conn->query('SELECT COUNT(*) AS c FROM api_tokens')->fetch_assoc();
    echo "Success: api_tokens exists and holds {$count['c']} token(s).\n";
    echo "Delete this file now.\n";
} else {
    echo "Error: api_tokens still missing after CREATE.\n";
}

$conn->close();
