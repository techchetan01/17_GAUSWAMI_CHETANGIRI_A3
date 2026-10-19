
<?php
$directory = "uploads";

if (!is_dir($directory)) {
    die("Directory does not exist!");
}

$files = scandir($directory);

echo "<h2>Files in Directory</h2>";

foreach ($files as $file) {
    if ($file != "." && $file != ".." && is_file($directory . "/" . $file)) {
        echo htmlspecialchars($file) . "<br>";
    }
}
?>
