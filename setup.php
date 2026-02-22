<?php
// setup.php
require 'includes/db.php';

try {
    // Admin
    $pass_admin = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE usuario = 'admin'");
    $stmt->execute([$pass_admin]);
    echo "Admin password updated to 'admin123'<br>";

    // Bailarines
    $pass_bailarin = password_hash('bailarin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE bailarines SET password = ?");
    $stmt->execute([$pass_bailarin]);
    echo "Bailarines password updated to 'bailarin123'<br>";

    echo "Setup complete. <a href='login.php'>Go to Login</a>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
