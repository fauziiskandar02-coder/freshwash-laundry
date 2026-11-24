<?php

// Ensure Vercel Serverless Function routing aligns with CodeIgniter 4
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF']    = '/index.php';

chdir(__DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
