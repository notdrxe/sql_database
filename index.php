<?php
$conn = new mysqli('localhost', 'root', '', 'school_management');
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_student'])) {
        $student_name = $conn->real_escape_string($_POST['student_name']);
        $stmt = $conn->prepare("INSERT INTO students (name) VALUES (?)");
        $stmt->bind_param('s', $student_name);
        $stmt->execute();
        $stmt->close();
        echo "Студент добавлен!";
    }


    if (isset($_POST['add_group'])) {
        $group_name = $conn->real_escape_string($_POST['group_name']);
        $stmt = $conn->prepare("INSERT INTO groups (name) VALUES (?)");
        $stmt->bind_param('s', $group_name);
        $stmt->execute();
        $stmt->close();
        echo "Группа добавлена!";
    }


    if (isset($_POST['assign_group'])) {
        $student_id = $_POST['student_id'];
        $group_id = $_POST['group_id'];
        $stmt = $conn->prepare("UPDATE students SET group_id = ? WHERE id = ?");
        $stmt->bind_param('ii', $group_id, $student_id);
        $stmt->execute();
        $stmt->close();
        echo "Студент привязан к группе!";
    }

    if (isset($_POST['register_course'])) {
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $stmt = $conn->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $student_id, $course_id);
        $stmt->execute();
        $stmt->close();
        echo "Студент зарегистрирован на курс!";
    }

    if (isset($_POST['delete_student'])) {
        $student_id = $_POST['student_id'];
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param('i', $student_id);
        $stmt->execute();
        $stmt->close();
        echo "Студент удален!";
    }

    if (isset($_POST['update_student_name'])) {
        $student_id = $_POST['student_id'];
        $new_name = $conn->real_escape_string($_POST['new_name']);
        $stmt = $conn->prepare("UPDATE students SET name = ? WHERE id = ?");
        $stmt->bind_param('si', $new_name, $student_id);
        $stmt->execute();
        $stmt->close();
        echo "Имя студента обновлено!";
    }

    if (isset($_POST['add_course'])) {
        $course_name = $conn->real_escape_string($_POST['course_name']);
        $stmt = $conn->prepare("INSERT INTO courses (name) VALUES (?)");
        $stmt->bind_param('s', $course_name);
        $stmt->execute();
        $stmt->close();
        echo "Курс добавлен!";
    }

    if (isset($_POST['add_teacher'])) {
        $teacher_name = $conn->real_escape_string($_POST['teacher_name']);
        $stmt = $conn->prepare("INSERT INTO teachers (name) VALUES (?)");
        $stmt->bind_param('s', $teacher_name);
        $stmt->execute();
        $stmt->close();
        echo "Преподаватель добавлен!";
    }


    if (isset($_POST['delete_course'])) {
        $course_id = $_POST['course_id'];
        $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param('i', $course_id);
        $stmt->execute();
        $stmt->close();
        echo "Курс удален!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>School Management</title>
</head>
<body>
    <h1>Управление школой</h1>
    <h2>Добавить нового студента</h2>
    <form method="POST">
        <input type="text" name="student_name" placeholder="Имя студента" required>
        <button type="submit" name="add_student">Добавить студента</button>
    </form>
    <h2>Список студентов</h2>
    <?php
$result = $conn->query("SELECT id, name FROM students");
    echo "<table border='1'><tr><th>ID</th><th>Имя</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td></tr>";
    }
    echo "</table>";
    ?>


    <h2>Добавить новую группу</h2>
    <form method="POST">
        <input type="text" name="group_name" placeholder="Название группы" required>
        <button type="submit" name="add_group">Добавить группу</button>
    </form>


    <h2>Привязать студента к группе</h2>
    <form method="POST">
        <label>Студент:</label>
        <select name="student_id">
            <?php
            $students = $conn->query("SELECT id, name FROM students");
            while ($row = $students->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <label>Группа:</label>
        <select name="group_id">
            <?php
            $groups = $conn->query("SELECT id, name FROM groups");
            while ($row = $groups->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="assign_group">Привязать</button>
    </form>

</body>
</html>