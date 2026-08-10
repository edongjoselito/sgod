<?php
// Run this script ONCE on the online server to create the api_tokens table.
// Upload to the web root and visit in your browser, then DELETE this file.
require_once 'application/config/database.php';

$conn = new mysqli(
    $db['default']['hostname'],
    $db['default']['username'],
    $db['default']['password'],
    $db['default']['database']
);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

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

if ($conn->query($sql) === TRUE) {
    echo "Success: api_tokens table created (or already exists).";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
