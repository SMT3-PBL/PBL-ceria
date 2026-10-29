<?php

require_once __DIR__ . '/../../../includes/init.php';

// Hapus semua data session
$_SESSION = [];

// Hapus session
session_destroy();

// Kembali ke halaman login
redirect('pages/auth/login.php');
