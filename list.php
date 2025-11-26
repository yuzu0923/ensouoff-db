<?php
require 'db_connect.php';

// 今日の日付（MySQL DATE 形式）
$today = date('Y-m-d');

$sql = "
SELECT *
FROM events
WHERE deadline >= :today
ORDER BY event_date ASC
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':today', $today, PDO::PARAM_STR);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style-list.css" />
  <title>募集中の演奏会一覧</title>
</head>

<body>
  <h1>🎶 募集中の演奏会一覧</h1>
  <hr class="list-divider">

  <?php if (count($events) > 0): ?>
    <!-- PC版 表形式 -->
    <div class="table-wrapper">
      <table class="list-table">
        <tr>
          <th>演奏会タイトル</th>
          <th>開催日</th>
          <th>都道府県</th>
          <th>募集締切日</th>
          <th>主催者</th>
          <th>応募率（※準備中）</th>
          <th>詳細</th>
        </tr>

        <?php
        $today = new DateTime();
        foreach ($events as $event):
          $deadline = !empty($event['deadline']) ? new DateTime($event['deadline']) : null;
          $days_left = $deadline ? $today->diff($deadline)->days : null;
          $is_soon = $deadline && $deadline >= $today && $days_left <= 7; // 7日以内を「もうすぐ締切」とする

          // 応募率（ランダムな値が仮入力される。実際はDBの応募テーブルから算出。）
          $progress = rand(10, 90);
        ?>
          <tr>
            <td><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($event['event_date'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($event['eventPrefecture'], ENT_QUOTES, 'UTF-8') ?></td>

            <!-- もうすぐ締切ならオレンジ表示 -->
            <td class="<?= $is_soon ? 'soon-deadline' : '' ?>">
              <?= htmlspecialchars($event['deadline'], ENT_QUOTES, 'UTF-8') ?>
              <?php if ($is_soon): ?>
                <div class="soon-label">⚠️もうすぐ締切</div>
              <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($event['organizer'], ENT_QUOTES, 'UTF-8') ?></td>

            <!-- 応募率ゲージ -->
            <td>
              <div class="progress-bar">
                <div class="progress" style="width: <?= $progress ?>%;"></div>
              </div>
              <div class="progress-text"><?= $progress ?>%</div>
            </td>

            <td><a class="detail-link" href="detail.php?id=<?= $event['id'] ?>">詳細</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>

    <!-- スマホ版 カード形式 -->
    <div class="card-list">
      <?php foreach ($events as $event): ?>
        <?php
        // もうすぐ締切判定
        $deadline = !empty($event['deadline']) ? new DateTime($event['deadline']) : null;
        $days_left = $deadline ? $today->diff($deadline)->days : null;
        $is_soon = $deadline && $deadline >= $today && $days_left <= 7;
        ?>
        <div class="event-card">

          <h2 class="card-title"><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></h2>
          <p class="card-date">
            📅 <?= htmlspecialchars($event['event_date'], ENT_QUOTES, 'UTF-8') ?>
          </p>

          <p class="card-place">
            📍 <?= htmlspecialchars($event['eventPrefecture'], ENT_QUOTES, 'UTF-8') ?>
          </p>

          <?php if ($is_soon): ?>
            <p class="card-deadline soon-deadline-label">⚠️もうすぐ締切</p>
          <?php endif; ?>

          <a class="detail-btn" href="detail.php?id=<?= $event['id'] ?>">詳細を見る</a>

        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <p>現在、登録されている演奏会はありません。</p>
  <?php endif; ?>

  <a class="top-link" href="index.php">トップページへ</a>

</body>

</html>