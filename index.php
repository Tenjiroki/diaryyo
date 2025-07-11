<?php

require __DIR__ . '/inc/db-connect.inc.php';
require __DIR__ . '/inc/functions.inc.php';

date_default_timezone_set('Europe/Kyiv');

$perPage = 3;
$page = (int) ($_GET['page'] ?? 1);
if($page < 1){$page=1;};
$offset = ($page - 1) * $perPage;

$datashow_count = $pdo->prepare(' SELECT COUNT(*) AS count FROM `entries`');
$datashow_count->execute();
$count = $datashow_count->fetch(PDO::FETCH_ASSOC)['count'];

$numPages = ceil($count/$perPage);

$datashow = $pdo->prepare('SELECT * FROM `entries` ORDER BY `occurence` DESC, `id` DESC LIMIT :perPage OFFSET :offset');
$datashow->bindValue('perPage', $perPage, PDO::PARAM_INT);
$datashow->bindValue('offset', $offset, PDO::PARAM_INT);
$datashow->execute();
$results = $datashow->fetchAll(PDO::FETCH_ASSOC);

 ?>

<?php require __DIR__ . '/views/header.view.php';?>

<h1 class="main-head">Entries</h1>
<?php foreach($results as $result): ?>
<div class="card">
  <?php if(!empty($result['image'])): ?>
  <div class="card_pic-container">
    <img class="card_pic" src="./uploads/<?php echo e($result['image']); ?>" alt="entry image" />
  </div>
  <?php endif;?>
  <div class="card_txt-container">
    <?php
      $dateExpl = explode('-', $result['occurence']);
      $timestamp = mktime(12, 0, 0, $dateExpl[1], $dateExpl[2], $dateExpl[0]);
    ?>
    <div class="card_txt-time"> <?php echo e(date('d/m/Y', $timestamp));?> </div>
    <h2 class="card_head"> <?php echo e($result['title']);?> </h2>
    <p class="card_txt_parag">
      <?php echo nl2br(e($result['story']));?>
    </p>
  </div>
</div>
<?php endforeach; ?>

<?php if ($numPages > 1):?>
  <ul class="pagination">
  <?php if($page > 1):?>
      <li class="pagination_li">
        <a
          href="index.php?<?php echo http_build_query(['page' => $page-1]); ?>"
          class="pagination_link">⇜</a>
      </li>
  <?php endif; ?>

    <?php for($x=1; $x<=$numPages; $x++): ?>
      <li class="pagination_li">
        <a
          class="pagination_link <?php if($page === $x): echo 'pagination_link-active'; endif; ?>"
          href="index.php?<?php echo http_build_query(['page' => $x]); ?>" class="pagination_link">
          <?php echo e($x); ?>
        </a>
      </li>
    <?php endfor; ?>

  <?php if($page < $numPages):?>
    <li class="pagination_li">
      <a href="index.php?<?php echo http_build_query(['page' => $page+1]); ?>" class="pagination_link">⇝</a>
    </li>
  <?php endif; ?>
  </ul>
<?php endif; ?>

<?php require __DIR__ . '/views/footer.view.php';?>
