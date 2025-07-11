<?php

require __DIR__ . '/inc/db-connect.inc.php';
require __DIR__ . '/inc/functions.inc.php';

if (!empty($_POST)){
  $title = (string) ($_POST['title'] ?? '');
  $occurence = (string) ($_POST['date'] ?? '');
  $story = (string) ($_POST['message'] ?? '');

  $infosend = $pdo->prepare('INSERT INTO `entries` (`title`, `occurence`, `story`) VALUES (:title, :occurence, :story)');
  $infosend->bindValue('title', $title);
  $infosend->bindValue('occurence', $occurence);
  $infosend->bindValue('story', $story);
  $infosend->execute();

  header('Location: index.php');
  exit;
}

?>

<?php require __DIR__ . '/views/header.view.php';?>

<h1 class="main-head">New Entry</h1>
<form method="POST" action="form.php">
  <div class="formgroup">
    <lable class="formgroup_lable" for="title">Title:</lable>
    <input class="formgroup_input" type="text" id="title" name="title" required/>
  </div>
  <div class="formgroup">
    <lable class="formgroup_lable" for="date">Date:</lable>
    <input class="formgroup_input" type="date" id="date" name="date" required/>
  </div>
  <div class="formgroup">
    <lable class="formgroup_lable" for="message">Message:</lable>
    <textarea class="formgroup_input" id="message" name="message" rows="6" required></textarea>
  </div>
  <div class="form_submit">
    <button class="button">
      <img src="./pics/send.png" class="button_pic"/>
      Submit
    </button>
  </div>
</form>

<?php require __DIR__ . '/views/footer.view.php';?>
