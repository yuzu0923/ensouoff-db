<?php
require 'db_connect.php';

// フォームの内容を受け取る
$title = $_POST['title'] ?? '';
$organizer = $_POST['organizer'] ?? '';
$deadline = $_POST['deadline'] ?? null;
$eventDate = $_POST['eventDate'] ?? null;
$eventDateNote = $_POST['eventDateNote'] ?? '';
$eventPrefecture = $_POST['eventPrefecture'] ?? '';
$location_note = $_POST['location_note'] ?? '';
$location_note2 = $_POST['location_note2'] ?? '';
$time_start_hour = $_POST['time_start_hour'] ?? null;
$time_start_min = $_POST['time_start_min'] ?? null;
$time_finish_hour = $_POST['time_finish_hour'] ?? null;
$time_finish_min = $_POST['time_finish_min'] ?? null;
$time_note = $_POST['time_note'] ?? '';
$concept = $_POST['concept'] ?? '';
$requirement_memo = $_POST['requirement_memo'] ?? '';
$genreSelect = $_POST['genreSelect'] ?? '';
$requirement = isset($_POST['requirement']) ? implode(',', $_POST['requirement']) : '';
$songTitle1 = $_POST['songTitle1'] ?? '';
$composer1 = $_POST['composer1'] ?? '';
$songMemo1 = $_POST['songMemo1'] ?? '';
$songTitle2 = $_POST['songTitle2'] ?? '';
$composer2 = $_POST['composer2'] ?? '';
$songMemo2 = $_POST['songMemo2'] ?? '';
$songTitle3 = $_POST['songTitle3'] ?? '';
$composer3 = $_POST['composer3'] ?? '';
$songMemo3 = $_POST['songMemo3'] ?? '';

$instrument = $_POST['instrument'] ?? [];
$instrument_counts = $_POST['instrument_counts'] ?? [];

$other_instrument_name = $_POST['other_instrument_name'] ?? '';
$other_instrument_count = $_POST['other_instrument_count'] ?? 0;

// 楽器（名前と人数）を文字列でまとめる
$selected_instruments = $_POST['instrument'] ?? [];
$instrument_with_counts = [];

foreach ($selected_instruments as $inst) {
    $count_key = "{$inst}_count";
    $count = $_POST[$count_key] ?? '';

    // 人数が入力されている場合と、空の場合を分ける
    if ($count !== '' && $count !== null) {
        $instrument_with_counts[] = "{$inst}({$count}名)";
    } else {
        $instrument_with_counts[] = "{$inst}";
    }
}

$instrument = implode(', ', $instrument_with_counts);

// データベースに挿入
$sql = "INSERT INTO events (
    title,
    deadline,
    organizer,
    event_date,
    event_date_note,
    eventPrefecture,
    location_note,
    location_note2,
    time_start_hour,
    time_start_min,
    time_finish_hour,
    time_finish_min,
    time_note,
    concept,
    requirement_memo,
    genreSelect,
    requirement,
    songTitle1,
    composer1,
    songMemo1,
    songTitle2,
    composer2,
    songMemo2,
    songTitle3,
    composer3,
    songMemo3,
    instrument,
    other_instrument_name,
    other_instrument_count
) VALUES (
    :title,
    :deadline,
    :organizer,
    :event_date,
    :event_date_note,
    :eventPrefecture,
    :location_note,
    :location_note2,
    :time_start_hour,
    :time_start_min,
    :time_finish_hour,
    :time_finish_min,
    :time_note,
    :concept,
    :requirement_memo,
    :genreSelect,
    :requirement,
    :songTitle1,
    :composer1,
    :songMemo1,
    :songTitle2,
    :composer2,
    :songMemo2,
    :songTitle3,
    :composer3,
    :songMemo3,
    :instrument,
    :other_instrument_name,
    :other_instrument_count
)";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':title', $title);
$stmt->bindValue(':deadline', $deadline);
$stmt->bindValue(':organizer', $organizer);
$stmt->bindValue(':event_date', $eventDate);
$stmt->bindValue(':event_date_note', $eventDateNote);
$stmt->bindValue(':eventPrefecture', $eventPrefecture);
$stmt->bindValue(':location_note', $location_note);
$stmt->bindValue(':location_note2', $location_note2);
$stmt->bindValue(':time_start_hour', $time_start_hour);
$stmt->bindValue(':time_start_min', $time_start_min);
$stmt->bindValue(':time_finish_hour', $time_finish_hour);
$stmt->bindValue(':time_finish_min', $time_finish_min);
$stmt->bindValue(':time_note', $time_note);
$stmt->bindValue(':concept', $concept);
$stmt->bindValue(':requirement_memo', $requirement_memo);
$stmt->bindValue(':genreSelect', $genreSelect);
$stmt->bindValue(':requirement', isset($requirement) ? json_encode($requirement, JSON_UNESCAPED_UNICODE) : '');
$stmt->bindValue(':songTitle1', $songTitle1);
$stmt->bindValue(':composer1', $composer1);
$stmt->bindValue(':songMemo1', $songMemo1);
$stmt->bindValue(':songTitle2', $songTitle2);
$stmt->bindValue(':composer2', $composer2);
$stmt->bindValue(':songMemo2', $songMemo2);
$stmt->bindValue(':songTitle3', $songTitle3);
$stmt->bindValue(':composer3', $composer3);
$stmt->bindValue(':songMemo3', $songMemo3);
$stmt->bindValue(':instrument', $instrument);
$stmt->bindValue(':other_instrument_name', $other_instrument_name ?? '');
$stmt->bindValue(':other_instrument_count', $other_instrument_count ?? 0);

$stmt->execute();


header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-insert.css" />
    <title>登録完了</title>

    <script>
        history.replaceState(null, null, location.href);
    </script>

</head>

<body>
    <div class="complete-container">
        <p class="complete-message">登録が完了しました！</p>
        <a href="index.php" class="go-top">トップページへ</a>
    </div>
</body>

</html>