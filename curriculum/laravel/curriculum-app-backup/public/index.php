<?php
// Public entry point for the CafeCafe application
// Redirect to the view directory

// Set the document root to the view directory
$viewPath = __DIR__ . '/../view/index.php';

if (file_exists($viewPath)) {
    // Include the main index file
    include $viewPath;
} else {
    // Fallback if the file doesn't exist
    echo "<h1>CafeCafe Application</h1>";
    echo "<p>The application is starting up...</p>";
    echo "<p>View path: " . $viewPath . "</p>";
}
?>
