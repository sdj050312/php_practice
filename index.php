<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = "test1234";
$dbName = 'postboard';

$conn = new mysqli($host, $user, $password, $dbName);
if ($conn->connect_error) {
  die("Connection failed" . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['user_name'];
  $pw = $_POST['password'];

  $sql = "select * from users where user_name = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if (!$row) {
    echo "<script>
      alert('일치하는 아이디가 없습니다.');
      history.back();
      </script>";
    exit;
  }

  // 비밀번호 검증 (password_verify 사용)
  if (!password_verify($pw, $row['password'])) {
    echo "<script>
      alert('비밀번호가 일치하지 않습니다.');
      history.back();
      </script>";
    exit;
  }

  // 로그인 성공 시 세션 생성
  $_SESSION['user_name'] = $row['user_name'];
  echo "<script>
  alert('로그인 성공!');
  window.location.href = './logout.php'; // 로그인 후 리디렉션
  </script>";
}

$conn->close();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>home</title>
  <link rel="stylesheet" href="./css/inedex.css" />
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
    <h1 class="title">Simple Project</h1>
    <section>
      <div class="login-container">
        <form class="login-box" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <h1>login</h1>
          <ul class="id-component">
            <li>アイディー</li>
            <li><input type="text" name="user_name" placeholder="アイディー" /></li>
          </ul>
          <ul>
            <li>パスワド</li>
            <li><input type="password" name="password" placeholder="パスワド" /></li>
          </ul>
          <button type="submit">Login</button>
        </form>
        <a href="./register.php">Do you have account?</a>
      </div>
    </section>
  </main>
</body>

</html>