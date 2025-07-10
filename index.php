<?php

require __DIR__ . '/inc/db-connect.inc.php';
require __DIR__ . '/inc/functions.inc.php';


$datashow = $pdo->prepare('SELECT * FROM `entries`');
$datashow->execute();
$results = $datashow->fetchAll(PDO::FETCH_ASSOC);
//var_dump($results);
 ?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./styles/normalize.css">
    <link rel="stylesheet" type="text/css" href="./styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;700&family=Manrope:wght@400;700&family=Literata:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <title>My Diary Yo</title>
</head>


<body>


  <nav class="nav">
    <div class="container">
      <div class="nav_lay">
        <a href="index.php" class="nav-head">
          <img src="./pics/logo.png" class="nav_pic"/>
          Diary Yo
        </a>
        <a href="form.php" class="button">
          <img src="./pics/add.png" class="button_pic"/>
          New Entry
        </a>
      </div>
    </div>
  </nav>


  <main class="main">
    <div class="container">
      <h1 class="main-head">Entries</h1>
      <?php foreach($results as $result): ?>
      <div class="card">
        <div class="card_pic-container">
          <img class="card_pic" src="./pics/0000.jpeg" class="button_pic" alt=""/>
        </div>
        <div class="card_txt-container">
          <div class="card_txt-time"> <?php echo e($result['occurence']);?> </div>
          <h2 class="card_head"> <?php echo e($result['title']);?> </h2>
          <p class="card_txt_parag">
            <?php echo nl2br(e($result['story']));?>
          </p>
        </div>
      </div>
    <?php endforeach; ?>

      <ul class="pagination">
        <li class="pagination_li">
          <a href="#" class="pagination_link">⇜</a>
        </li>
        <li class="pagination_li">
          <a href="#" class="pagination_link">1</a>
        </li>
        <li class="pagination_li">
          <a href="#" class="pagination_link">2</a>
        </li>
        <li class="pagination_li">
          <a href="#" class="pagination_link">3</a>
        </li>
        <li class="pagination_li">
          <a href="#" class="pagination_link">⇝</a>
        </li>
      </ul>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <h3 class="foot_head">Sander's Diary</h3>
      <p class="foot_txt">I am learning PHP and need some actual works for my portfolio so here we go my dear friends:)</p>
    </div>
  </footer>



</body>
</html>
