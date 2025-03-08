<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = 'test1234';
$dbname = "postboard";

// 데이터베이스 연결
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 로그인 상태 확인 (이미 로그인한 경우 리디렉션)
if (isset($_SESSION['user_name'])) {
  echo "<script>
    alert('이미 로그인 하셨습니다.');
    window.location.href = './index.php';
    </script>";
  exit;
}

// 회원가입 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['user_name'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  // 아이디 중복 확인
  $check_sql = "SELECT user_name FROM users WHERE user_name = ?";
  $check_stmt = $conn->prepare($check_sql);
  $check_stmt->bind_param("s", $username);
  $check_stmt->execute();
  $check_stmt->store_result();
  if ($username === "" || $password == "") {
    echo "<script>
      alert('해당 태그에 값을 입력해주세여');
    </script>";
    exit;
  }
  if ($check_stmt->num_rows > 0) {
    echo "<script>alert('이미 존재하는 아이디입니다.'); window.history.back();</script>";
    $check_stmt->close();
    exit;
  }
  $check_stmt->close();

  // 비밀번호 해싱 (보안 강화)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Prepared Statement 사용 (SQL Injection 방지)
  $sql = "INSERT INTO users (user_name, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    echo "<script>alert('회원가입 성공!'); window.location.href = './index.php';</script>";
  } else {
    $error_message = addslashes($stmt->error);
    echo "<script>alert('회원가입 실패: {$error_message}');</script>";
  }

  $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>会員登録</title>
  <link rel="stylesheet" href="./css/inedex.css" />
  <link rel="stylesheet" href="./css/register.css" />
</head>

<body>

  <header>
    <ul>
      <li><a href="./index.php">home</a></li>
      <li><a href="./board.php">board</a></li>
      <li><a href="./write.php">Write</a></li>
    </ul>

    <ul>
      <li><a href="./index.php">Login</a></li>
      <li><a href="./admin.php">admin</a></li>
    </ul>
  </header>

  <main class="register-container">
    <div class="register-boxs">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h1>会員登録</h1>
        <div class="register-form">
          <ul>
            <li>ID</li>
            <li>パスワード</li>
            <li>メールアドレス</li>
          </ul>
          <ul>
            <li><input type="text" name="user_name" placeholder="アイディー" required /></li>
            <li><input type="password" name="password" placeholder="パスワード" required /></li>
            <li><input type="email" name="email" placeholder="メールアドレス" required /></li>
          </ul>
        </div>
        <button type="submit">登録</button>
      </form>
      <a href="./index.php">ログインはこちら</a>
    </div>
  </main>

</body>

</html>