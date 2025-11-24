<?php
require 'db_connect.php';

// id が指定されていない場合
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  exit('不正なアクセスです。');
}

$id = (int)$_GET['id'];

// データを取得
$sql = "SELECT * FROM events WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// データが存在しない場合
if (!$event) {
  exit('指定された演奏会は存在しません。');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/style-detail.css">
  <title><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?> - 演奏会の詳細</title>
</head>

<body>
  <h1>🎵 <?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></h1>
  <hr class="divider">
  <div class="detail-container">
    <dl>
      <dt>締切日</dt>
      <dd>
        <?php
        $deadlineObj = new DateTime($event['deadline']);
        $weekday = ['日', '月', '火', '水', '木', '金', '土'][$deadlineObj->format('w')];
        echo htmlspecialchars($deadlineObj->format('Y年m月d日') . "（{$weekday}）", ENT_QUOTES, 'UTF-8');
        ?>
      </dd>
      <dt>主催者</dt>
      <dd><?= htmlspecialchars($event['organizer'], ENT_QUOTES, 'UTF-8') ?></dd>
      <dt>開催日</dt>
      <dd>
        <?php
        $dateObj = new DateTime($event['event_date']);
        $weekday = ['日', '月', '火', '水', '木', '金', '土'][$dateObj->format('w')];
        echo htmlspecialchars($dateObj->format('Y年m月d日') . "（{$weekday}）", ENT_QUOTES, 'UTF-8');
        ?>
        <br>
        （※備考：
        <?php
        echo !empty($event['event_date_note'])
          ? htmlspecialchars($event['event_date_note'], ENT_QUOTES, 'UTF-8')
          : '−';
        ?>
        ）
      </dd>

      <dt>開催場所</dt>
      <dd><?= htmlspecialchars($event['eventPrefecture'], ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars($event['location_note'], ENT_QUOTES, 'UTF-8') ?>（<?= htmlspecialchars($event['location_note2'], ENT_QUOTES, 'UTF-8') ?>）</dd>

      <dt>時間</dt>
      <dd><?= htmlspecialchars($event['time_start_hour'], ENT_QUOTES, 'UTF-8') ?>時<?= htmlspecialchars($event['time_start_min'], ENT_QUOTES, 'UTF-8') ?>分～<?= htmlspecialchars($event['time_finish_hour'], ENT_QUOTES, 'UTF-8') ?>時<?= htmlspecialchars($event['time_finish_min'], ENT_QUOTES, 'UTF-8') ?>分<br>（※備考：<?= htmlspecialchars($event['time_note'], ENT_QUOTES, 'UTF-8') ?>）</dd>

      <dt>ジャンル</dt>
      <!-- 吹奏楽以外のジャンルは都度追加 -->
      <dd>
        <?php
        $genreLabels = [
          "winds" => "吹奏楽",
          // "strings" => "弦楽",
          // "brass" => "金管バンド",
          // "orchestra" => "オーケストラ",
          // "ensemble" => "アンサンブル",
        ];

        $genre = $event['genreSelect'] ?? "";
        $genreDisplay = $genreLabels[$genre] ?? htmlspecialchars($genre, ENT_QUOTES, 'UTF-8');
        ?>
        <?= $genreDisplay ?>
      </dd>


      <dt>コンセプト</dt>
      <dd><?= htmlspecialchars($event['concept'], ENT_QUOTES, 'UTF-8') ?></dd>

      <dt>雰囲気</dt>
      <?php
      $requirementRaw = $event['requirement'] ?? "";
      $requirementFormatted = str_replace(",", "、", htmlspecialchars($requirementRaw, ENT_QUOTES, 'UTF-8'));
      ?>
      <dd><?= $requirementFormatted ?></dd>


      <dt>演奏曲①</dt>
      <dd>
        <?= displayOrDash($event['songTitle1']) ?>
        （<?= displayOrDash($event['composer1']) ?>、<?= displayOrDash($event['songMemo1']) ?>）
      </dd>
      <dt>演奏曲②</dt>
      <dd>
        <?= displayOrDash($event['songTitle2']) ?>
        （<?= displayOrDash($event['composer2']) ?>、<?= displayOrDash($event['songMemo2']) ?>）
      </dd>
      <dt>演奏曲③</dt>
      <dd>
        <?= displayOrDash($event['songTitle3']) ?>
        （<?= displayOrDash($event['composer3']) ?>、<?= displayOrDash($event['songMemo3']) ?>）
      </dd>


      <dt>募集楽器</dt>
      <!-- カンマ区切りを改行に変換 -->
      <dd>
        <?php
        $list = explode(',', $event['instrument']);
        foreach ($list as $item) {
          echo htmlspecialchars(trim($item), ENT_QUOTES, 'UTF-8') . "<br>";
        }
        ?>
      </dd>

      <?php
      // 値が空なら「-」を返す関数
      function displayOrDash($value)
      {
        $value = trim($value);
        return $value === "" ? "-" : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
      }
      ?>
    </dl>
  </div>

  <div class="apply-section">
    <button class="apply-button" disabled>🎵 応募する（準備中）</button>
    <p class="apply-note">※ 現在、応募機能は準備中です。</p>
  </div>

  <div class="btn-area">
    <a href="list.php">一覧に戻る</a>
    <a href="index.php">トップページへ</a>
  </div>


</body>

</html>