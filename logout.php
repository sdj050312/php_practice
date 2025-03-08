<?php
session_start();
$_SESSION = array();
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>user</title>
  <link rel="stylesheet" href="./css/inedex.css" />
  <link rel="stylesheet" href="./css/logout.css" />
</head>

<body>
  <header>
    <ul class="">
      <li><a href="./index.php">home</a></li>
      <li><a href="./board.php">board</a></li>
      <li><a href="./write.php">Write</a></li>
    </ul>

    <ul>
      <?php if (isset($_SESSION['user_name'])): ?>
        <li><a href="./logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="./index.php">Login</a></li>
      <?php endif; ?>
      <li><a href="./admin.php">admin</a></li>
    </ul>
  </header>
  <div class="logout-container">
    <h1>歓迎します！ITの勉強の為に作ってました！</h1>
    <a href="./board.php">開始版見に行く</a>
  </div>
</body>

</html>