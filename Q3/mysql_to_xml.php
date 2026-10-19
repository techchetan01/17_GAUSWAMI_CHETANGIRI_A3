
<?php
$conn = new mysqli("localhost", "root", "root", "datest");

if ($conn->connect_error) {
    die("Connection : " . $conn->connect_error);
}

$result = $conn->query("SELECT id, name, email, course FROM students");

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><students/>');

while ($row = $result->fetch_assoc()) {
    $student = $xml->addChild("student");

    $student->addChild("id", $row["id"]);
    $student->addChild("name", htmlspecialchars($row["name"], ENT_XML1, "UTF-8"));
    $student->addChild("email", htmlspecialchars($row["email"], ENT_XML1, "UTF-8"));
    $student->addChild("course", htmlspecialchars($row["course"], ENT_XML1, "UTF-8"));
}

$xml->asXML("students.xml");

echo "MySQL data exported to students.xml successfully!";

$conn->close();
?>
