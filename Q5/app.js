
const express = require("express");
const app = express();

app.use(express.json());

app.get("/students", (req, res) => {
    const students = [
        { id: 1, name: "test", course: "MSc ICT" },
        { id: 2, name: "test", course: "BCA" },
        { id: 3, name: "test", course: "BSc IT" }
    ];

    res.json(students);
});

app.listen(3000, () => {
    console.log("Express server running on port 3000");
});
