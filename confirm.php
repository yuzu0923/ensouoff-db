<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>確認画面</title>
</head>

<body>
  <link rel="stylesheet" href="css/style-confirm.css" />
  <div class="all">

    <?php
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];
    $organizer = $_POST['organizer'];
    $eventDate = $_POST['eventDate'];
    $eventDateNote = $_POST['eventDateNote'];
    $location_note = $_POST['location_note'];
    $location_note2 = $_POST['location_note2'];

    $concept = $_POST['concept'] ?? '';
    $requirement_display = $_POST['requirement_display'] ?? '';
    $requirement_memo = $_POST['requirement_memo'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $requirement = $_POST['requirement'] ?? []; // 選択された雰囲気（配列）
    $requirement_display = implode('、', $requirement); // 「、」区切りで表示用に整形
    $requirement_memo = $_POST['requirement_memo'] ?? '';

    $songTitle1 = $_POST['songTitle1'];
    $composer1 = $_POST['composer1'];
    $songMemo1 = $_POST['songMemo1'];
    $songTitle2 = $_POST['songTitle2'];
    $composer2 = $_POST['composer2'];
    $songMemo2 = $_POST['songMemo2'];
    $songTitle3 = $_POST['songTitle3'];
    $composer3 = $_POST['composer3'];
    $songMemo3 = $_POST['songMemo3'];

    $eventRegion = $_POST['eventRegion'] ?? '';
    $eventPrefecture = $_POST['eventPrefecture'] ?? '';

    $deadline_raw = $_POST['deadline'];
    $deadline_obj = new DateTime($deadline_raw);

    $eventDate_raw = $_POST['eventDate'];
    $eventDate_obj = new DateTime($eventDate_raw);

    // 曜日マッピング
    $day_map = [
      'Mon' => '月',
      'Tue' => '火',
      'Wed' => '水',
      'Thu' => '木',
      'Fri' => '金',
      'Sat' => '土',
      'Sun' => '日'
    ];

    // 締切日 日本語化
    $english_day_deadline = $deadline_obj->format('D');
    $japanese_day_deadline = $day_map[$english_day_deadline];
    $deadline_display = $deadline_obj->format("Y年n月j日（{$japanese_day_deadline}）");

    // 開催日 日本語化
    $english_day_event = $eventDate_obj->format('D');
    $japanese_day_event = $day_map[$english_day_event];
    $eventDate_display = $eventDate_obj->format("Y年n月j日（{$japanese_day_event}）");






    $time_start_hour = $_POST['time_start_hour'];
    $time_start_min = $_POST['time_start_min'];
    $time_finish_hour = $_POST['time_finish_hour'];
    $time_finish_min = $_POST['time_finish_min'];
    $time_note = $_POST['time_note'];

    $time_start = sprintf('%02d:%02d', $time_start_hour, $time_start_min);
    $time_finish = sprintf('%02d:%02d', $time_finish_hour, $time_finish_min);


    $genreSelect = $_POST['genreSelect'] ?? '';

    $genreLabels = [
      'winds' => '吹奏楽',
      'orchestra' => 'オーケストラ（準備中）',
      'band' => 'バンド（準備中）',
    ];

    // 表示用ラベル（不明ならそのまま表示）
    $genreDisplay = $genreLabels[$genreSelect] ?? $genreSelect;


    ?>






    <h1>入力内容の確認</h1>

    <fieldset>
      <h2>１：基本情報</h2>
      <p>■演奏会タイトル：<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></p>
      <p>■募集締切日：<?= htmlspecialchars($deadline_display, ENT_QUOTES, 'UTF-8') ?></p>
      <p>■主催者：<?= nl2br(htmlspecialchars($organizer, ENT_QUOTES, 'UTF-8')) ?></p>
      <!-- 「nl2br」は改行コード -->
    </fieldset>

    <fieldset>
      <h2>２：演奏会の詳細</h2>
      <p>■開催日程：<?= htmlspecialchars($eventDate_display, ENT_QUOTES, 'UTF-8') ?></p>
      <!-- ↑後でdeadlineと同じ処理をしておく！ -->
      <p>（※備考）：<?= htmlspecialchars($eventDateNote, ENT_QUOTES, 'UTF-8') ?></p>
      <p>■開催場所：<?= htmlspecialchars($eventPrefecture, ENT_QUOTES, 'UTF-8') ?></p>
      <p>■住所：<?= htmlspecialchars($location_note, ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars($location_note2, ENT_QUOTES, 'UTF-8') ?></p>

      <p>■開催時間</p>
      <p>・開始：<?= htmlspecialchars($time_start, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・終了：<?= htmlspecialchars($time_finish, ENT_QUOTES, 'UTF-8') ?></p>
      <p>（※備考）：<?= htmlspecialchars($time_note, ENT_QUOTES, 'UTF-8') ?></p>
    </fieldset>


    <fieldset>
      <h2>３：イベントの詳細</h2>
      <p>■ジャンル：<?= htmlspecialchars($genreDisplay, ENT_QUOTES, 'UTF-8') ?></p>
      <p>■コンセプト：<?= htmlspecialchars($concept, ENT_QUOTES, 'UTF-8') ?></p>
      <p>■雰囲気：<?= $requirement_display ?></p>
      <p>（※備考）：<?= htmlspecialchars($requirement_memo, ENT_QUOTES, 'UTF-8') ?></p>
    </fieldset>

    <fieldset>
      <h2>４：演奏曲目</h2>
      <p>●１曲目：</p>
      <p>・曲名：<?= htmlspecialchars($songTitle1, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・作曲者：<?= htmlspecialchars($composer1, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・備考：<?= htmlspecialchars($songMemo1, ENT_QUOTES, 'UTF-8') ?></p>

      <p>●２曲目：</p>
      <p>・曲名：<?= htmlspecialchars($songTitle2, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・作曲者：<?= htmlspecialchars($composer2, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・備考：<?= htmlspecialchars($songMemo2, ENT_QUOTES, 'UTF-8') ?></p>

      <p>●３曲目：</p>
      <p>・曲名：<?= htmlspecialchars($songTitle3, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・作曲者：<?= htmlspecialchars($composer3, ENT_QUOTES, 'UTF-8') ?></p>
      <p>・備考：<?= htmlspecialchars($songMemo3, ENT_QUOTES, 'UTF-8') ?></p>
    </fieldset>

    <fieldset>
      <h2>５：募集楽器・人数</h2>
      <?php
      if (!empty($_POST['instrument']) && is_array($_POST['instrument'])) {
        echo "<p>■参加楽器</p>";
        echo "<ul>";
        foreach ($_POST['instrument'] as $instrument) {
          // 楽器名に対応する人数を取得
          $count_key = $instrument . "_count";
          $count = isset($_POST[$count_key]) ? (int)$_POST[$count_key] : 0;

          // instrument[] に選ばれた項目のうち、人数が1以上のものだけが表示されます。
          $count_key = $instrument . "_count";
          $count = isset($_POST[$count_key]) ? (int)$_POST[$count_key] : 0;

          if (!empty($_POST[$count_key]) && $count > 0) {
            echo "<li>" . htmlspecialchars($instrument, ENT_QUOTES, 'UTF-8') . "：{$count}名</li>";
          }
        }
        echo "</ul>";
      }
      // ▼ ここから自由入力の処理 ▼
      if (!empty($_POST['other_instrument_name']) && (int)$_POST['other_instrument_count'] > 0) {
        $name = htmlspecialchars($_POST['other_instrument_name'], ENT_QUOTES, 'UTF-8');
        $count = (int)$_POST['other_instrument_count'];
        echo "<p>■その他の楽器</p>";
        echo "<ul><li>{$name}：{$count}名</li></ul>";
      }
      ?>

    </fieldset>




    <!-- 「送信する」ボタンの処理 -->
    <form action="insert.php" method="post">
      <input type="hidden" name="title" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="organizer" value="<?= htmlspecialchars($organizer, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="eventDate" value="<?= htmlspecialchars($eventDate, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="eventDateNote" value="<?= htmlspecialchars($eventDateNote, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="location_note" value="<?= htmlspecialchars($location_note, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="location_note2" value="<?= htmlspecialchars($location_note2, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songTitle1" value="<?= htmlspecialchars($songTitle1, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="composer1" value="<?= htmlspecialchars($composer1, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songMemo1" value="<?= htmlspecialchars($songMemo1, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="songTitle2" value="<?= htmlspecialchars($songTitle2, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="composer2" value="<?= htmlspecialchars($composer2, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songMemo2" value="<?= htmlspecialchars($songMemo2, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="songTitle3" value="<?= htmlspecialchars($songTitle3, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="composer3" value="<?= htmlspecialchars($composer3, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songMemo3" value="<?= htmlspecialchars($songMemo3, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="eventRegion" value="<?= htmlspecialchars($eventRegion, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="eventPrefecture" value="<?= htmlspecialchars($eventPrefecture, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="time_start_hour" value="<?= htmlspecialchars($time_start_hour, ENT_QUOTES) ?>">
      <input type="hidden" name="time_start_min" value="<?= htmlspecialchars($time_start_min, ENT_QUOTES) ?>">
      <input type="hidden" name="time_finish_hour" value="<?= htmlspecialchars($time_finish_hour, ENT_QUOTES) ?>">
      <input type="hidden" name="time_finish_min" value="<?= htmlspecialchars($time_finish_min, ENT_QUOTES) ?>">
      <input type="hidden" name="time_note" value="<?= htmlspecialchars($time_note, ENT_QUOTES) ?>">

      <input type="hidden" name="concept" value="<?= htmlspecialchars($concept, ENT_QUOTES) ?>">
      <input type="hidden" name="requirement_memo" value="<?= htmlspecialchars($requirement_memo, ENT_QUOTES) ?>">

      <input type="hidden" name="genreSelect" value="<?= htmlspecialchars($genreSelect, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="other_instrument_name" value="<?= htmlspecialchars($other_instrument_name ?? '', ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="other_instrument_count" value="<?= htmlspecialchars($_other_instrument_count ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <!-- チェックボックス保持用 hidden -->
      <?php if (!empty($requirement)): ?>
        <?php foreach ($requirement as $r): ?>
          <input type="hidden" name="requirement[]" value="<?= htmlspecialchars($r, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if (!empty($_POST['instrument'])): ?>
        <?php foreach ($_POST['instrument'] as $instrument): ?>
          <input type="hidden" name="instrument[]" value="<?= htmlspecialchars($instrument, ENT_QUOTES, 'UTF-8') ?>">
          <input type="hidden" name="<?= htmlspecialchars($instrument, ENT_QUOTES, 'UTF-8') ?>_count"
            value="<?= htmlspecialchars($_POST[$instrument . '_count'] ?? 0, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
      <?php endif; ?>
      <div class="buttons">
        <button type="submit">送信する</button>
      </div>
    </form>

    <form action="register.php" method="post">
      <input type="hidden" name="title" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="deadline" value="<?= htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="organizer" value="<?= htmlspecialchars($organizer, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="eventDate" value="<?= htmlspecialchars($eventDate, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="eventDateNote" value="<?= htmlspecialchars($eventDateNote, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="location_note" value="<?= htmlspecialchars($location_note, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="location_note2" value="<?= htmlspecialchars($location_note2, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songTitle1" value="<?= htmlspecialchars($songTitle1, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="composer1" value="<?= htmlspecialchars($composer1, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songMemo1" value="<?= htmlspecialchars($songMemo1, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="songTitle2" value="<?= htmlspecialchars($songTitle2, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="composer2" value="<?= htmlspecialchars($composer2, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songMemo2" value="<?= htmlspecialchars($songMemo2, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="songTitle3" value="<?= htmlspecialchars($songTitle3, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="composer3" value="<?= htmlspecialchars($composer3, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="songMemo3" value="<?= htmlspecialchars($songMemo3, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="eventRegion" value="<?= htmlspecialchars($eventRegion, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="eventPrefecture" value="<?= htmlspecialchars($eventPrefecture, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="time_start_hour" value="<?= htmlspecialchars($time_start_hour, ENT_QUOTES) ?>">
      <input type="hidden" name="time_start_min" value="<?= htmlspecialchars($time_start_min, ENT_QUOTES) ?>">
      <input type="hidden" name="time_finish_hour" value="<?= htmlspecialchars($time_finish_hour, ENT_QUOTES) ?>">
      <input type="hidden" name="time_finish_min" value="<?= htmlspecialchars($time_finish_min, ENT_QUOTES) ?>">
      <input type="hidden" name="time_note" value="<?= htmlspecialchars($time_note, ENT_QUOTES) ?>">

      <input type="hidden" name="concept" value="<?= htmlspecialchars($concept, ENT_QUOTES) ?>">
      <input type="hidden" name="requirement_memo" value="<?= htmlspecialchars($requirement_memo, ENT_QUOTES) ?>">

      <input type="hidden" name="genreSelect" value="<?= htmlspecialchars($genreSelect, ENT_QUOTES, 'UTF-8') ?>">

      <input type="hidden" name="other_instrument_name" value="<?= htmlspecialchars($_other_instrument_name ?? '', ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="other_instrument_count" value="<?= htmlspecialchars($_other_instrument_count ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <!-- チェックボックス保持用 hidden -->
      <?php if (!empty($requirement)): ?>
        <?php foreach ($requirement as $r): ?>
          <input type="hidden" name="requirement[]" value="<?= htmlspecialchars($r, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if (!empty($_POST['instrument'])): ?>
        <?php foreach ($_POST['instrument'] as $instrument): ?>
          <input type="hidden" name="instrument[]" value="<?= htmlspecialchars($instrument, ENT_QUOTES, 'UTF-8') ?>">
          <input type="hidden" name="<?= htmlspecialchars($instrument, ENT_QUOTES, 'UTF-8') ?>_count"
            value="<?= htmlspecialchars($_POST[$instrument . '_count'] ?? 0, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
      <?php endif; ?>

      <div class="buttons">
        <button type="submit">戻って修正する</button>
      </div>
    </form>
  </div>
</body>

</html>