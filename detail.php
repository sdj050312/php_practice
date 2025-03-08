<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = 'test1234';
$dbname = 'postboard';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// GET 파라미터에서 게시글 ID 가져오기
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 유효한 ID인지 확인
if ($id <= 0) {
  die("無効なIDです。");
}

// 게시글 조회 쿼리
$sql = "SELECT id, board_title, board_content, created_at FROM bd_board WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>詳細ページ</title>
  <link rel="stylesheet" href="./css/detail.css" />
  <link rel="stylesheet" href="./css/inedex.css" /> <!-- 파일명 수정 -->
</head>

<body>
  <header>
    <ul>
      <li><a href="/">home</a></li>
      <li><a href="./board.php">board</a></li>
      <li><a href="./write.php">Write</a></li>
    </ul>

    <ul>
      <?php if (isset($_SESSION['user_name'])): ?>
        <li><a href="./logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="./login.php">Login</a></li>
      <?php endif; ?>
      <li><a href="./admin.php">admin</a></li>
    </ul>
  </header>
  <main>
    <div class="detail-container">
      <h1>詳細ページ</h1>

      <?php if ($row): ?>
        <h3><?= htmlspecialchars($row['board_title']) ?></h3>
        <div class="detail-component">
          <p><?= nl2br(htmlspecialchars($row['board_content'])) ?></p>
        </div>
      <?php else: ?>
        <p>この投稿は存在しません。</p>
      <?php endif; ?>

    </div>
    <div class="comment-container">
      <h3>コメント欄</h3>
      <form action="">
        <input type="text" placeholder="コメントに書いてください" />
        <button type="submit">アープロード</button>
      </form>
    </div>
    <div class="comment-list">
      <h2>コメント欄</h2>
      <ul class="comment">
        <li><span>user:</span>え？マジ？</li>
        <li>dsfd</li>
      </ul>
    </div>
  </main>
</body>

</html>