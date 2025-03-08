<?php
session_start(); // 세션 시작

$host = 'localhost';
$user = 'root';
$password = 'test1234';
$dbName = 'postboard';

$conn = new mysqli($host, $user, $password, $dbName);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// POST 요청을 받으면 게시글 저장
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $content = $_POST['content'];

  // 게시글 삽입 SQL 쿼리 (title, content를 posts 테이블에 추가)
  $sql = "INSERT INTO bd_board (board_title, board_content) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $title, $content);

  if ($stmt->execute()) {
    echo "<script>alert('게시글이 성공적으로 작성되었습니다.'); window.location.href = './board.php';</script>";
  } else {
    echo "<script>alert('게시글 작성 실패: " . $stmt->error . "');</script>";
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Write</title>
  <link rel="stylesheet" href="./css/write.css" />
  <link rel="stylesheet" href="./css/inedex.css" />
</head>

<body>

  <header>
    <ul>
      <li><a href="./index.php">Home</a></li>
      <li><a href="./board.php">Board</a></li>
      <li><a href="./write.php">Write</a></li>
    </ul>
    <ul>
      <?php if (isset($_SESSION['user_name'])): ?>
        <li><a href="./logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="./login.php">Login</a></li>
      <?php endif; ?>
      <li><a href="./admin.php">Admin</a></li>
    </ul>
  </header>

  <main>
    <div class="board-box">
      <h1>開始版</h1>
      <form action="./write.php" method="POST" class="board-form">
        <h3>作成者</h3>
        <input type="text" name="title" placeholder="作成者" required />
        <h3>作文の内容を書いてください</h3>
        <textarea name="content" placeholder="何を書けば良いかな～～" id="board" required></textarea>
        <button type="submit">提出</button>
      </form>
    </div>
  </main>

</body>

</html>