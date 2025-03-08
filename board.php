<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = 'test1234';
$dbName = 'postboard';

$conn = new mysqli($host, $user, $password, $dbName);
if ($conn->connect_error) {
  die("connenction failed" . $conn->connect_error);
}

$sql = "SELECT id, board_title, board_content, created_at FROM bd_board ORDER BY created_at DESC";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>board</title>
  <link rel="stylesheet" href="./css/board.css" />
  <link rel="stylesheet" href="./css/inedex.css" />
</head>

<body>
  <header>
    <ul>
      <li><a href="./index.php">home</a></li>
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
    <h1>開始版リスト</h1>
    <div class="search">
      <select name="title" id="board_list">
        <option value="date">日程</option>
        <option value="comment">内容</option>
        <option value="title">テーマ</option>
      </select>
      <form class="search_container">
        <input type="text" placeholder="検索欄">
        <button type="submit">検索</button>
      </form>
    </div>
    <table>
      <thead>
        <tr>
          <th>id</th>
          <th>title</th>
          <th>content</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody class="board_list">
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";

            // URL 경로 수정 (현재 디렉토리에 detail.php가 있다고 가정)
            echo "<td><a href='detail.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['board_title']) . "</a></td>";

            // board_content를 처음 10자만 표시하고, 10자를 넘으면 '...' 추가
            $contentPreview = substr($row['board_content'], 0, 10);
            echo "<td>" . htmlspecialchars($contentPreview) . (strlen($row['board_content']) > 10 ? "..." : "") . "</td>";

            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='4'>게시글이 없습니다</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </main>
</body>

</html>

<?php
$conn->close();
?>