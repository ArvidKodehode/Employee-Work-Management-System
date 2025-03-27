<?php
try {
    $db = new PDO("sqlite:db/users.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DB connection failed: " . $e->getMessage());
}
?>
