<?php
// Dynamically determine the base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_name = $_SERVER['SCRIPT_NAME'];
$project_folder = dirname(dirname($script_name)); // Go up two levels from /core/config.php to the project root

// If the project is in a subfolder, the folder name will be part of the path.
// If it's at the root, dirname will return '/' or '\'. We normalize it to an empty string.
$base_path = ($project_folder === '/' || $project_folder === '\\') ? '' : $project_folder;

define('BASE_URL', $protocol . $host . $base_path);
?>
