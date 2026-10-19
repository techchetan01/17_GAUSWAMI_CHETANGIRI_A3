
<?php
$conn = new mysqli("localhost", "root", "root", "datest");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$xml = simplexml_load_file("students.xml");

if ($xml === false) {
    die("Unable to read students.xml");
}

$stmt = $conn->prepare(
    "INSERT INTO students (id, name, email, course)
     VALUES (?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
     name = VALUES(name),
     email = VALUES(email),
     course = VALUES(course)"
);

$stmt->bind_param("isss", $id, $name, $email, $course);

foreach ($xml->student as $student) {
    $id = (int)$student->id;
    $name = (string)$student->name;
    $email = (string)$student->email;
    $course = (string)$student->course;

    $stmt->execute();
}

echo "XML data stored in MySQL successfully!";

$stmt->close();
$conn->close();
?>
