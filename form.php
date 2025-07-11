<?php
require __DIR__ . '/inc/db-connect.inc.php';
require __DIR__ . '/inc/functions.inc.php';

if (!empty($_POST)){
  $title = (string) ($_POST['title'] ?? '');
  $occurence = (string) ($_POST['date'] ?? '');
  $story = (string) ($_POST['message'] ?? '');
  $imageFilename = null;

  if (!empty($_FILES) && !empty($_FILES['image'])) {
      if ($_FILES['image']['error'] === 0 && $_FILES['image']['size'] !== 0) {
          $nameWithoutExtension = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
          $name = preg_replace('/[^a-zA-Z0-9]/', '', $nameWithoutExtension);
          $originalImage = $_FILES['image']['tmp_name'];

          $imageInfo = getimagesize($originalImage);

          if ($imageInfo === false) {
              die('Invalid image file');
          }

          $mime = $imageInfo['mime'];

          $ext = match($mime) {
              'image/jpeg' => 'jpg',
              'image/png' => 'png',
              'image/gif' => 'gif',
              default => die('Unsupported image type: ' . $mime),
          };

          $imageFilename = $name . '-' . time() . '.' . $ext;
          $destImage = __DIR__ . '/uploads/' . $imageFilename;

          if (!is_dir(__DIR__ . '/uploads/')) {
              mkdir(__DIR__ . '/uploads/', 0755, true);
          }

          [$width, $height] = [$imageInfo[0], $imageInfo[1]];
          $maxDim = 400;
          $scaleFactor = $maxDim / max($width, $height);
          $newWidth = (int)($width * $scaleFactor);
          $newHeight = (int)($height * $scaleFactor);

          $im = match($mime) {
              'image/jpeg' => imagecreatefromjpeg($originalImage),
              'image/png' => imagecreatefrompng($originalImage),
              'image/gif' => imagecreatefromgif($originalImage),
          };

          if (!$im) {
              die('Failed to create image from file');
          }

          $newImg = imagecreatetruecolor($newWidth, $newHeight);

          if ($mime === 'image/png' || $mime === 'image/gif') {
              imagealphablending($newImg, false);
              imagesavealpha($newImg, true);
              $transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
              imagefill($newImg, 0, 0, $transparent);
          }

          imagecopyresampled($newImg, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

          if (!imagejpeg($newImg, $destImage, 85)) {
              die('Failed to save resized image');
          }

          imagedestroy($im);
          imagedestroy($newImg);
      }
  }

  $infosend = $pdo->prepare('INSERT INTO `entries` (`title`, `occurence`, `story`, `image`) VALUES (:title, :occurence, :story, :image)');
  $infosend->bindValue('title', $title);
  $infosend->bindValue('occurence', $occurence);
  $infosend->bindValue('story', $story);
  $infosend->bindValue('image', $imageFilename);
  $infosend->execute();
  header('Location: index.php');
  exit;
}
?>
<?php require __DIR__ . '/views/header.view.php';?>
<h1 class="main-head">New Entry</h1>
<form method="POST" action="form.php" enctype="multipart/form-data">
  <div class="formgroup">
    <label class="formgroup_lable" for="title">Title:</label>
    <input class="formgroup_input" type="text" id="title" name="title" required/>
  </div>
  <div class="formgroup">
    <label class="formgroup_lable" for="date">Date:</label>
    <input class="formgroup_input" type="date" id="date" name="date" required/>
  </div>
  <div class="formgroup">
    <label class="formgroup_lable" for="image">Image:</label>
    <input class="formgroup_input" type="file" id="image" name="image" accept="image/*"/>
  </div>
  <div class="formgroup">
    <label class="formgroup_lable" for="message">Message:</label>
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
