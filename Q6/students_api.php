
<?php
header("Content-Type: application/json; charset=UTF-8");

$students = [
    ["id" => 1, "name" => "test", "course" => "MSc ICT"],
    ["id" => 2, "name" => "test", "course" => "BCA"],
    ["id" => 3, "name" => "test", "course" => "BSc IT"]
];

echo json_encode($students);
?>
