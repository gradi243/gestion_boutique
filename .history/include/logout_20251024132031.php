<?php
// demarre la session
session_start();

// supprime les variables de la session
session_unset();

// Détruit la session
session_destroy();

// Redirige vers la page de connexion
header("Location: login.php");
exit;
?>