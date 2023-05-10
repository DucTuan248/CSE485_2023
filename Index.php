<?php
require_once 'Student.php';
require_once 'StudentDAO.php';

// Tạo đối tượng StudentDAO để lưu trữ danh sách sinh viên
$studentDAO = new StudentDAO();

// Mở file studentDAO.txt để đọc dữ liệu
$handle = fopen('Data.csv', 'r');

// Đọc các dòng dữ liệu từ file và lưu vào đối tượng StudentDAO
while (($data = fgetcsv($handle)) !== false) {
  // Tạo đối tượng Student từ dữ liệu trong file
  $id = $data[0];
  $name = $data[1];
  $age = $data[2];
  $grade = $data[3];
  $student = new Student($id, $name, $age, $grade);
  
  // Thêm đối tượng Student vào đối tượng StudentDAO
  $studentDAO->create($student);
}

// Đóng file sau khi đọc xong
fclose($handle);

// Hiển thị danh sách sinh viên lên trang web
$students = $studentDAO->getAll();
// Kiểm tra xem có dữ liệu được gửi đi từ form hay không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Lấy dữ liệu từ form
  $id = $_POST['id'];
  $name = $_POST['name'];
  $age = $_POST['age'];
  $grade = $_POST['grade'];

  // Mở file studentDAO.txt để đọc dữ liệu
  $handle = fopen('Data.csv', 'r');
  $studentDAO = new StudentDAO();
  // Đọc các dòng dữ liệu từ file và lưu vào đối tượng StudentDAO
  while (($data = fgetcsv($handle)) !== false) {
    // Tạo đối tượng Student từ dữ liệu trong file
    $student = new Student($data[0], $data[1], $data[2], $data[3]);

    // Thêm đối tượng Student vào đối tượng StudentDAO
    $studentDAO->create($student);
  }

  // Đóng file sau khi đọc xong
  fclose($handle);

  // Kiểm tra trùng lặp với sinh viên có cùng ID
  if ($studentDAO->getById($id) == null) {
    // Tạo đối tượng Student từ dữ liệu trong form
    $student = new Student($id, $name, $age, $grade);
  
    // Thêm đối tượng Student mới vào đối tượng StudentDAO
    $studentDAO = new StudentDAO();
    $studentDAO->create($student);

    // Lưu danh sách sinh viên vào file Data.csv
    $students = $studentDAO->getAll();
    $file = fopen('Data.csv', 'a');
    foreach ($students as $student) {
      fwrite($file, $student->getId() . ',' . $student->getName() . ',' . $student->getAge() . ',' . $student->getGrade() . PHP_EOL);
    }
    fclose($file);

    // Chuyển hướng về trang danh sách sinh viên
    header('Location: Index.php');
    exit();
  } else {
    echo '<p style="color: red;">Student ID already exists!</p>';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên mới</title>
   
</head>
<body>
    <h1 style="text-align: center;">Thêm sinh viên mới</h1>
    <form style=" display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 50%;
  margin: 0 auto;" method="post" action="save.php">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required>
        <br>
<label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>
        <br>
        <label for="grade">Grade:</label>
        <input type="text" id="grade" name="grade" required>
        <br>
        <button style="padding: 0.5em;" type="submit">Save</button>
        <a style="padding: 0.5em;" href="index.php">Exit</a>
    </form>
</body>
</html>
