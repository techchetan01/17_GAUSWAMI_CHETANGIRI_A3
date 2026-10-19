
<?php
$url = "http://localhost:9090/test";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    die("API Error: " . curl_error($ch));
}

curl_close($ch);

if ($status != 200) {
    die("API returned HTTP status: " . $status);
}

$students = json_decode($response, true);

echo "<h2>Students from Express API</h2>";

foreach ($students as $student) {
    echo "ID: " . (int)$student["id"] . "<br>";
    echo "Name: " . htmlspecialchars($student["name"]) . "<br>";
    echo "Course: " . htmlspecialchars($student["course"]) . "<br><hr>";
}
?>
