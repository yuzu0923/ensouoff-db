<?php
require 'db_connect.php';
$today = date('Y-m-d');

// 「新着の募集」欄に最新の募集を2件取得（締切が今日か、今日以降）
// 取得したデータから日付を参照し、ORDER BYで並べ替え指示。DESCは降順、ASCは昇順。
$sql = "
  SELECT id, title, event_date, eventPrefecture, created_at
  FROM events
  WHERE deadline >= :today
  ORDER BY created_at DESC
  LIMIT 2
";
$stmt = $pdo->prepare($sql); // 実行準備
$stmt->bindValue(':today', $today, PDO::PARAM_STR); // SQL内のプレースホルダー名、PHPの変数、文字列
$stmt->execute(); // SQLの実行
$new_events = $stmt->fetchAll(PDO::FETCH_ASSOC); // 配列の取得
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>演奏オフ募集データベース</title> <!-- タブ名 -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <header class="header">
    <img class="logo" src="music_trumpet.png" alt="フリー素材のトランペットアイコンです。「いらすとや」様よりフリー素材をお借りしています。ここが演奏会に関するサイトであることを示しています。">
    <h1>演奏オフ募集データベース</h1>
    <button class="menu">≡ メニュー<br>（準備中）</button>
    <!-- 今後コンテンツが増えたらハンバーガーメニューを実装します -->
  </header>

  <main>
    <p>&emsp;演奏オフ募集データベースへようこそ！<br>&emsp;ここは一日限りの演奏会の企画や、演奏メンバーの募集ができるサイトです。
      <br>&emsp;ルールを守って楽しく使いましょう。（管理人：○○○○）
    </p>

    <!-- メインメニュー -->
    <section class="mainMenu">
      <h2>メインメニュー</h2>
      <div class="buttons">
        <a href="register.php" class="btn">演奏会を<br>企画する</a>
        <a href="list.php" class="btn">演奏会を<br>さがす</a>
        <a class="btn">マイページ<br>（準備中）</a>
        <!-- アカウント登録後、募集状況や応募状況・内容の変更が可能なマイページを実装したいです。 -->
        <a href="about.php" class="btn">このサイトに<br>ついて</a>
      </div>
    </section>

    <!-- 新着情報（NEWS） -->
    <section class="news">
      <h2>新着情報（NEWS）</h2>
      <ul class="news-list">
        <li><a href="news.php#notice-20251109">■2025.11.09&emsp;【重要】追加機能の実装スケジュールについて</a></li>
        <li><a href="news.php#notice-20250720">■2025.07.20&emsp;テンプレート「吹奏楽」を実装しました！</a></li>
        <li><a href="news.php#notice-20250201">■2025.02.01&emsp;サイトを公開しました！</a></li>
        <li><a href="news.php#notice-20250101">■2025.01.01&emsp;サイトオープン日が決定！</a></li>
      </ul>
      <p class="more-news"><a href="news.php">→ 過去のお知らせを見る</a></p>
    </section>

    <!-- 新着の募集 -->
    <section class="articles">
      <h2>新着の募集</h2>
      <div class="article-grid">
        <!-- 登録データから最新の演奏会情報を２件抜粋してトップページに掲載する -->
        <?php if (!empty($new_events)): ?>
          <!-- $new_eventsが空かどうか判断し、空であれば下部「現在、新着の募集はありません」を表示 -->
          <?php foreach ($new_events as $event): ?>
            <!-- $new_eventsを1件ずつ取り出してカードに表示する（最大2件）、それぞれイベント詳細ページにリンク、カードに表示 -->
            <a href="detail.php?id=<?= htmlspecialchars($event['id'], ENT_QUOTES, 'UTF-8') ?>" class="article-card">
              <h3><?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8') ?></h3>
              <p>
                <?= $event['event_date']
                  ? htmlspecialchars(date('Y年n月j日', strtotime($event['event_date'])), ENT_QUOTES, 'UTF-8')
                  : '日程未定' ?>
                <?= !empty($event['place']) ? '（' . htmlspecialchars($event['place'], ENT_QUOTES, 'UTF-8') . '）' : '' ?>
              </p>
              <small>
                登録：<?= htmlspecialchars(date('Y-m-d', strtotime($event['created_at'])), ENT_QUOTES, 'UTF-8') ?>
              </small>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <p>現在、新着の募集はありません。</p>
        <?php endif; ?>
      </div>
    </section>

    <!-- サイトマップ -->
    <section class="sitemap">
      <h2>サイトマップ</h2>
      <ul>
        <li><a href="index.php">トップページ</a></li>
        <li><a href="register.php">演奏会を企画する</a></li>
        <li><a href="list.php">演奏会をさがす</a></li>
        <li><a>マイページ（準備中）</a></li>
        <li><a href="about.php">このサイトについて</a></li>
        <li><a>お問い合わせ（不具合報告もこちらから）</a></li>
        <!-- Googleformで問い合わせフォームを作成予定。 -->
      </ul>
    </section>
  </main>

  <footer class="index-footer">
    <small>制作：2025年11月</small>
  </footer>
</body>
</html>