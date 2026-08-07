<?php defined('BASEPATH') OR exit('No direct script access allowed');

// $config = array(
//     'protocol' => 'ssmtp', // 'mail', 'sendmail', or 'smtp'
//     'smtp_host' => 'ssl://ssmtp.googlemail.com', 
//     'smtp_port' => 465,
//     'smtp_user' => 'depedroxidev@gmail.com',
//     'smtp_pass' => 'Caballero18',
//     'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
//     'mailtype' => 'text', //plaintext 'text' mails or 'html'
//     'smtp_timeout' => '4', //in seconds
//     'charset' => 'iso-8859-1',
//     'wordwrap' => TRUE
// );

// SMTP sender settings for partner signup
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'mail.depedmis.com';
$config['smtp_user'] = 'one@depedmis.com';
$config['smtp_pass'] = 'moth34board';
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'ssl';

// Manual outgoing settings:
// Username: one@depedmis.com
// Password: use the email account's password
// Outgoing Server: mail.depedmis.com
// SMTP Port: 465
// SSL/TLS required

// legacy Gmail setup
// $config['protocol'] = 'smtp';
// $config['smtp_host'] = 'ssl://smtp.googlemail.com';
// $config['smtp_user'] = 'softtechservices.net@gmail.com';
// $config['smtp_pass'] = '.P0liceReport';
// $config['smtp_port'] = 465;

$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['newline'] = "\r\n"; 
