<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>開催情報登録フォーム</title>
</head>

<body>
  <form action="confirm.php" method="POST">
    <!-- 入力データの送信方法と送信形式を指定 -->
    <link rel="stylesheet" href="css/style-register.css" />
    <h2>開催情報登録フォーム</h2>
    <h4>&ensp; こちらは、演奏会の開催情報を登録するフォームです。<br>&ensp; 演奏会の趣旨や内容がよくわかるように、各項目はなるべく詳しく記載してください。</h4>

    <!-- １：基本情報 -->
    <fieldset>
      <legend>１：基本情報</legend>
      <label for="title">■演奏会タイトル<span style="color:red;">*</span></label> <!-- 【span】は部分装飾 -->
      <input type="text" id="title" name="title" required placeholder="(例)バッハを演奏しよう！の会" value="<?= htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES) ?>"><br><br> <!-- 【required】未記入の場合,バリデーションエラー（入力値の検証エラー）が起きる -->
      <!-- 【htmlspecialchars】HTMLにおいて特殊な性質をもつ文字を文字列に変換して表示する。【ENT_QUOTES】シングルクオートとダブルクオートを共に変換 -->

      <label for="deadline">■募集締切日<span style="color: red;">*</span></label>
      <input type="date" id="deadline" name="deadline" required value="<?= isset($_POST['deadline']) ? htmlspecialchars($_POST['deadline'], ENT_QUOTES, 'UTF-8') : '' ?>"><br><br>
      <!-- 【isset】変数が宣言されていて、nullではないかどうかの検証。 -->

      <label for="organizer">■主催者<span style="color: red;">*</span></label>
      <input type="text" id="organizer" name="organizer" placeholder="主催者の氏名" required value="<?= htmlspecialchars($_POST['organizer'] ?? '', ENT_QUOTES) ?>"><br><br>
    </fieldset>
    <!-- ↑ここまで↑ -->

    <!-- ２：演奏会の詳細 -->
    <fieldset>
      <legend>２：演奏会の詳細</legend>
      <label for="eventDate">■開催日程<span style="color: red;">*</span></label>
      <input type="date" id="eventDate" name="eventDate" required value="<?= htmlspecialchars($_POST['eventDate'] ?? '', ENT_QUOTES) ?>"><br>
      <textarea name="eventDateNote" required placeholder="開催日に関する備考"><?= htmlspecialchars($_POST['eventDateNote'] ?? '', ENT_QUOTES) ?></textarea><br><br>

      <label for="eventArea">■開催場所</label>
      <label for="eventRegion">・地方を選択：<span style="color: red;">*</span></label>
      <select name="eventRegion" id="eventRegion" required onchange="updatePrefecture()">
        <!-- HTMLの select 要素（＝ドロップダウン）において「選択を変更したときに updatePrefecture()という関数を実行する」という命令 -->
        <option value="">-- 選択してください --</option>
        <option value="hokkaitohoku" <?= ($_POST['eventRegion'] ?? '') === 'hokkaitohoku' ? 'selected' : '' ?>>北海道・東北</option>
        <option value="kanto" <?= ($_POST['eventRegion'] ?? '') === 'kanto' ? 'selected' : '' ?>>関東</option>
        <option value="chubu" <?= ($_POST['eventRegion'] ?? '') === 'chubu' ? 'selected' : '' ?>>中部</option>
        <option value="kinki" <?= ($_POST['eventRegion'] ?? '') === 'kinki' ? 'selected' : '' ?>>近畿</option>
        <option value="chugoku" <?= ($_POST['eventRegion'] ?? '') === 'chugoku' ? 'selected' : '' ?>>中国</option>
        <option value="shikoku" <?= ($_POST['eventRegion'] ?? '') === 'sikoku' ? 'selected' : '' ?>>四国</option>
        <option value="kyushu" <?= ($_POST['eventRegion'] ?? '') === 'kyushu' ? 'selected' : '' ?>>九州・沖縄</option>
      </select><br>
      <!-- 最上段のvalue="" は「空文字列（＝ユーザーがまだ何も選んでいない状態）」を意味する。この <option> は、ドロップダウンの「初期表示用」であり、実際の選択肢ではないことを示すために、空の値を設定。 -->

      <label for="eventPrefecture">・都道府県を選択：<span style="color: red;">*</span></label>
      <select name="eventPrefecture" id="eventPrefecture" required>
        <option value="" disabled selected>-- まず地方を選んでください --</option>
      </select>

      <!-- ２：におけるscript -->
      <script>
        const previousRegion = <?= json_encode($_POST['eventRegion'] ?? '') ?>;
        const previousPrefecture = <?= json_encode($_POST['eventPrefecture'] ?? '') ?>;
        const prefectures = {
          hokkaitohoku: ["北海道", "青森県", "岩手県", "宮城県", "秋田県", "山形県", "福島県"],
          kanto: ["茨城県", "栃木県", "群馬県", "埼玉県", "千葉県", "東京都", "神奈川県"],
          chubu: ["新潟県", "富山県", "石川県", "福井県", "山梨県", "長野県", "岐阜県", "静岡県", "愛知県"],
          kinki: ["三重県", "滋賀県", "京都府", "大阪府", "兵庫県", "奈良県", "和歌山県"],
          chugoku: ["鳥取県", "島根県", "岡山県", "広島県", "山口県"],
          shikoku: ["徳島県", "香川県", "愛媛県", "高知県"],
          kyushu: ["福岡県", "佐賀県", "長崎県", "熊本県", "大分県", "宮崎県", "鹿児島県", "沖縄県"]
        };

        function updatePrefecture() { // 地方の選択に応じて都道府県の中身を更新する関数
          const regionSelect = document.getElementById("eventRegion"); // 地方のセレクトボックスの中身を取得
          const prefectureSelect = document.getElementById("eventPrefecture"); // 都道府県セレクトボックスの中身を取得
          const selectedRegion = regionSelect.value; // 現在選択されている地方を取得
          prefectureSelect.innerHTML = ""; // 都道府県セレクトボックスを一旦リセット

          if (selectedRegion && prefectures[selectedRegion]) {
            // 地方選択済、かつ対応する都道府県リストが存在する場合、都道府県を一つずつ並べる処理を行う。
            prefectures[selectedRegion].forEach(pref => {
              const option = document.createElement("option");
              option.value = pref;
              option.textContent = pref;
              if (pref === previousPrefecture) {
                option.selected = true;
              }
              prefectureSelect.appendChild(option);
            });
          } else { // 地方が未選択の場合に、案内文だけを表示
            const option = document.createElement("option");
            option.textContent = "-- まず地方を選んでください --";
            option.value = "";
            prefectureSelect.appendChild(option);
          }
        }
        // 初期状態で地方と都道府県を復元
        document.addEventListener("DOMContentLoaded", function() {
          if (previousRegion !== "") {
            document.getElementById("eventRegion").value = previousRegion;
            updatePrefecture(); // 地方に基づいて都道府県を生成

            // 少し待ってから都道府県の選択を設定（生成後に作動）
            setTimeout(() => {
              document.getElementById("eventPrefecture").value = previousPrefecture;
            }, 0);
          }
        });
      </script>

      <input type="text" name="location_note" id="location_note" required placeholder="住所" value="<?= htmlspecialchars($_POST['location_note'] ?? '', ENT_QUOTES) ?>"><br>
      <input type="text" name="location_note2" id="location_note2" required placeholder="建物・部屋名" value="<?= htmlspecialchars($_POST['location_note2'] ?? '', ENT_QUOTES) ?>"><br><br>


      <label for="time">■開催時間</label>
      <label for="time_start_hour">・集合時間<span style="color: red;">*</span></label>

      <?php
      $time_start_hour  = $_POST['time_start_hour']  ?? '';
      $time_start_min   = $_POST['time_start_min']   ?? '';
      $time_finish_hour = $_POST['time_finish_hour'] ?? '';
      $time_finish_min  = $_POST['time_finish_min']  ?? '';
      ?>

      <div class="inline-field">
        <select name="time_start_hour" id="time_start_hour" required>
          <option value="">--時--</option>

          <option value="08" <?= ($time_start_hour === "08") ? "selected" : "" ?>>08</option>
          <option value="09" <?= ($time_start_hour === "09") ? "selected" : "" ?>>09</option>
          <option value="10" <?= ($time_start_hour === "09") ? "selected" : "" ?>>10</option>
          <option value="11" <?= ($time_start_hour === "11") ? "selected" : "" ?>>11</option>
          <option value="12" <?= ($time_start_hour === "12") ? "selected" : "" ?>>12</option>
          <option value="13" <?= ($time_start_hour === "13") ? "selected" : "" ?>>13</option>
          <option value="14" <?= ($time_start_hour === "14") ? "selected" : "" ?>>14</option>
          <option value="15" <?= ($time_start_hour === "15") ? "selected" : "" ?>>15</option>
          <option value="16" <?= ($time_start_hour === "16") ? "selected" : "" ?>>16</option>
          <option value="17" <?= ($time_start_hour === "17") ? "selected" : "" ?>>17</option>
          <option value="18" <?= ($time_start_hour === "18") ? "selected" : "" ?>>18</option>
        </select>
        <select name="time_start_min" id="time_start_min" required>
          <option value="">--分--</option>

          <option value="00" <?= ($time_start_min === "00") ? "selected" : "" ?>>00</option>
          <option value="15" <?= ($time_start_min === "15") ? "selected" : "" ?>>15</option>
          <option value="30" <?= ($time_start_min === "30") ? "selected" : "" ?>>30</option>
          <option value="45" <?= ($time_start_min === "45") ? "selected" : "" ?>>45</option>
        </select>
      </div>

      <label for="time_finish_hour">・解散時間<span style="color: red;">*</span></label>
      <div class="inline-field">
        <select name="time_finish_hour" id="time_finish_hour" required>
          <option value="">--時--</option>
          <option value="13" <?= ($time_finish_hour === "13") ? "selected" : "" ?>>13</option>
          <option value="14" <?= ($time_finish_hour === "14") ? "selected" : "" ?>>14</option>
          <option value="15" <?= ($time_finish_hour === "15") ? "selected" : "" ?>>15</option>
          <option value="16" <?= ($time_finish_hour === "16") ? "selected" : "" ?>>16</option>
          <option value="17" <?= ($time_finish_hour === "17") ? "selected" : "" ?>>17</option>
          <option value="18" <?= ($time_finish_hour === "18") ? "selected" : "" ?>>18</option>
          <option value="19" <?= ($time_finish_hour === "19") ? "selected" : "" ?>>19</option>
          <option value="20" <?= ($time_finish_hour === "20") ? "selected" : "" ?>>20</option>
          <option value="21" <?= ($time_finish_hour === "21") ? "selected" : "" ?>>21</option>
        </select>
        <select name="time_finish_min" id="time_finish_min" required>
          <option value="">--分--</option>
          <option value="00" <?= ($time_finish_min === "00") ? "selected" : "" ?>>00</option>
          <option value="15" <?= ($time_finish_min === "15") ? "selected" : "" ?>>15</option>
          <option value="30" <?= ($time_finish_min === "30") ? "selected" : "" ?>>30</option>
          <option value="45" <?= ($time_finish_min === "45") ? "selected" : "" ?>>45</option>
        </select>
      </div>
      <br>
      <textarea name="time_note" placeholder="時間についての備考"><?= htmlspecialchars($_POST['time_note'] ?? '', ENT_QUOTES) ?></textarea>

      <br><br>
    </fieldset>

    <!-- イベントの詳細 -->
    <fieldset>
      <?php
      $genreSelect = $_POST['genreSelect'] ?? '';
      ?>

      <legend>３：イベントの詳細</legend>
      <label for="genre">■ジャンル<span style="color: red;">*</span></label>
      <select id="genreSelect" name="genreSelect" required>
        <option value="" <?= ($genreSelect === '') ? 'selected' : '' ?>>-- 選択してください --</option>
        <option value="winds" <?= ($genreSelect === 'winds') ? 'selected' : '' ?>>吹奏楽</option>
        <option value="orchestra" disabled <?= ($genreSelect === 'orchestra') ? 'selected' : '' ?>>オーケストラ（準備中）</option>
        <option value="band" disabled <?= ($genreSelect === 'band') ? 'selected' : '' ?>>バンド（準備中）</option>
      </select><br><br>

      <label for="concept">■コンセプト</label>
      <textarea name="concept" placeholder="(例)バッハの曲ばかり演奏する会です。バッハ大好きな人、集まれ！"><?= htmlspecialchars($_POST['concept'] ?? '', ENT_QUOTES) ?></textarea><br>

      <label for="requirement">■雰囲気</label>
      <?php
      $requirement = $_POST['requirement'] ?? []; // 配列として初期化
      ?>
      <div>
        <p><input type="checkbox" name="requirement[]" value="初心者歓迎" <?= in_array("初心者歓迎", $requirement) ? 'checked' : '' ?>>&emsp;初心者歓迎</p>
      </div>
      <div>
        <p><input type="checkbox" name="requirement[]" value="経験者歓迎" <?= in_array("経験者歓迎", $requirement) ? 'checked' : '' ?>>&emsp;経験者歓迎</p>
      </div>
      <div>
        <p><input type="checkbox" name="requirement[]" value="エンジョイ" <?= in_array("エンジョイ", $requirement) ? 'checked' : '' ?>>&emsp;エンジョイ</p>
      </div>
      <div>
        <p><input type="checkbox" name="requirement[]" value="本気で演奏" <?= in_array("本気で演奏", $requirement) ? 'checked' : '' ?>>&emsp;本気で演奏</p>
      </div>
      <textarea name="requirement_memo"><?= htmlspecialchars($_POST['requirement_memo'] ?? '', ENT_QUOTES) ?></textarea><br><br>
    </fieldset>

    <fieldset>
      <legend>４：演奏曲目</legend>
      <div class="songlist">
        <label for="song">●１曲目<span style="color: red;">*</span></label>
        <!-- 曲名の入力欄 -->
        <div class="inline-field">
          <p>曲&emsp;名：</p>
          <textarea name="songTitle1" placeholder="曲名" required><?= htmlspecialchars($_POST['songTitle1'] ?? '', ENT_QUOTES) ?></textarea><br>
        </div>
        <!-- 作曲者の入力欄 -->
        <div class="inline-field">
          <p>作曲者：</p>
          <textarea name="composer1" placeholder="作曲者" required><?= htmlspecialchars($_POST['composer1'] ?? '', ENT_QUOTES) ?></textarea><br>
        </div>
        <!-- 備考の入力欄 -->
        <div class="inline-field">
          <p>備&emsp;考：</p>
          <textarea name="songMemo1" placeholder="備考"><?= htmlspecialchars($_POST['songMemo1'] ?? '', ENT_QUOTES) ?></textarea><br><br>
        </div>
      </div><br>

      <div class="songlist">
        <label for="song">●２曲目</label>
        <!-- 曲名の入力欄 -->
        <div class="inline-field">
          <p>曲&emsp;名：</p>
          <textarea name="songTitle2" placeholder="曲名"><?= htmlspecialchars($_POST['songTitle2'] ?? '', ENT_QUOTES) ?></textarea><br>
        </div>
        <!-- 作曲者の入力欄 -->
        <div class="inline-field">
          <p>作曲者：</p>
          <textarea name="composer2" placeholder="作曲者"><?= htmlspecialchars($_POST['composer2'] ?? '', ENT_QUOTES) ?></textarea><br>
        </div>
        <!-- 備考の入力欄 -->
        <div class="inline-field">
          <p>備&emsp;考：</p>
          <textarea name="songMemo2" placeholder="備考"><?= htmlspecialchars($_POST['songMemo2'] ?? '', ENT_QUOTES) ?></textarea><br><br>
        </div>
      </div><br>

      <div class="songlist">
        <label for="song">●３曲目</label>
        <!-- 曲名の入力欄 -->
        <div class="inline-field">
          <p>曲&emsp;名：</p>
          <textarea name="songTitle3" placeholder="曲名"><?= htmlspecialchars($_POST['songTitle3'] ?? '', ENT_QUOTES) ?></textarea><br>
        </div>
        <!-- 作曲者の入力欄 -->
        <div class="inline-field">
          <p>作曲者：</p>
          <textarea name="composer3" placeholder="作曲者"><?= htmlspecialchars($_POST['composer3'] ?? '', ENT_QUOTES) ?></textarea><br>
        </div>
        <!-- 備考の入力欄 -->
        <div class="inline-field">
          <p>備&emsp;考：</p>
          <textarea name="songMemo3" placeholder="備考"><?= htmlspecialchars($_POST['songMemo3'] ?? '', ENT_QUOTES) ?></textarea><br><br>
        </div>
      </div><br>
    </fieldset>

    <fieldset>
      <legend>募集楽器・人数</legend>
      <!-- 木管楽器 -->
      <fieldset>
        <legend>木管楽器</legend>
        <div id="woodwinds-section" class="instrument-section">
          <div id="woodwinds-container"></div>
        </div>
      </fieldset>

      <fieldset>
        <!-- 金管楽器 -->
        <legend>金管楽器</legend>
        <div id="brass-section" class="instrument-section">
          <div id="brass-container"></div>
        </div>
      </fieldset>

      <fieldset>
        <!-- 打楽器 -->
        <legend>打楽器</legend>
        <div id="percussion-section" class="instrument-section">
          <div id="percussion-container"></div>
        </div>
      </fieldset>

      <fieldset>
        <!-- その他の楽器 -->
        <legend>その他の楽器</legend>
        <div id="other-instruments-section" class="instrument-section">
          <div id="other-instruments-container">
            <?php
            // 「その他の楽器」にPOSTデータがあれば復元、なければ空欄を表示
            $other_name = isset($_POST['other_instrument_name']) ? htmlspecialchars($_POST['other_instrument_name'], ENT_QUOTES, 'UTF-8') : '';
            $other_count = isset($_POST['other_instrument_count']) ? (int)$_POST['other_instrument_count'] : '';
            ?>
            <div class="instrument-group">
              <input type="text" name="other_instrument_name" placeholder="楽器名" value="<?= $other_name ?>">
              <input type="number" name="other_instrument_count" min="0" placeholder="人数" value="<?= $other_count ?>">
            </div>
          </div>
        </div>
      </fieldset>
      
      <button type="submit" id="to-confirm">確認画面へ進む</button>
      </div>
  </form>
  </fieldset>


  <script>
    const postedInstruments = <?= json_encode($_POST['instrument'] ?? []); ?>;
    // 【postedInstruments】チェックがONだった楽器名の配列
    const postedCounts = <?= json_encode($_POST ?? []); ?>; // PHP→JSの値受け渡し

    document.addEventListener("DOMContentLoaded", function() {
      // ===== 木管楽器 =====
      const woodwinds = [
        "ピッコロ", "フルート", "アルトフルート", "オーボエ", "ファゴット", "Esクラリネット", "B♭クラリネット",
        "バスクラリネット", "コントラアルトクラリネット", "コントラバスクラリネット", "ソプラノサックス",
        "アルトサックス", "テナーサックス", "バリトンサックス"
      ]; // 特殊文字「♭」などはなるべく安全な文字列に置き換えてやりとりした方が良い
      const woodwindsContainer = document.getElementById("woodwinds-container");
      woodwindsContainer.innerHTML = ""; // コンテナ定義、重複生成を避けるため一旦空にする

      woodwinds.forEach(instrument => {
        const div = document.createElement("div");
        div.className = "instrument-group";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "instrument[]";
        checkbox.value = instrument; // チェックした楽器名を送信するため文字列に。
        checkbox.id = `checkbox-${instrument}`;

        if (postedInstruments.includes(instrument)) {
          checkbox.checked = true;
        } // POST に含まれている楽器であればチェックを入れて復元（戻る処理）

        const label = document.createElement("label");
        label.textContent = instrument;
        label.style.marginLeft = "0.5em";
        label.setAttribute("for", `checkbox-${instrument}`);

        const numberInput = document.createElement("input");
        numberInput.type = "number";
        numberInput.name = `${instrument}_count`; // 「楽器名_count」のようにPHPへ送信する。
        numberInput.min = 0;
        numberInput.placeholder = "人数";

        const countKey = `${instrument}_count`;
        if (postedCounts[countKey]) {
          numberInput.value = postedCounts[countKey];
        } // 人数の復元。0を考慮しない。

        div.appendChild(checkbox);
        div.appendChild(label);
        div.appendChild(numberInput);
        woodwindsContainer.appendChild(div);
      });

      // ===== 金管楽器 =====
      const brass = [
        "トランペット", "コルネット", "フリューゲルホルン", "ホルン",
        "トロンボーン", "バストロンボーン", "ユーフォニアム", "チューバ"
      ];
      const brassContainer = document.getElementById("brass-container");
      brassContainer.innerHTML = "";

      brass.forEach(instrument => {
        const div = document.createElement("div");
        div.className = "instrument-group";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "instrument[]";
        checkbox.value = instrument;
        checkbox.id = `checkbox-${instrument}`;

        if (postedInstruments.includes(instrument)) {
          checkbox.checked = true;
        }

        const label = document.createElement("label");
        label.textContent = instrument;
        label.style.marginLeft = "0.5em";
        label.setAttribute("for", `checkbox-${instrument}`);

        const numberInput = document.createElement("input");
        numberInput.type = "number";
        numberInput.name = `${instrument}_count`;
        numberInput.min = 0;
        numberInput.placeholder = "人数";

        const countKey = `${instrument}_count`;
        if (postedCounts[countKey]) {
          numberInput.value = postedCounts[countKey];
        }

        div.appendChild(checkbox);
        div.appendChild(label);
        div.appendChild(numberInput);
        brassContainer.appendChild(div);
      });

      // ===== 打楽器 =====
      const percussion = ["打楽器", "打楽器（鍵盤系）", "打楽器（太鼓系）"];
      const percussionContainer = document.getElementById("percussion-container");
      percussionContainer.innerHTML = "";

      percussion.forEach(instrument => {
        const div = document.createElement("div");
        div.className = "instrument-group";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "instrument[]";
        checkbox.value = instrument;
        checkbox.id = `checkbox-${instrument}`;

        if (postedInstruments.includes(instrument)) {
          checkbox.checked = true;
        }

        const label = document.createElement("label");
        label.textContent = instrument;
        label.style.marginLeft = "0.5em";
        label.setAttribute("for", `checkbox-${instrument}`);

        const numberInput = document.createElement("input");
        numberInput.type = "number";
        numberInput.name = `${instrument}_count`;
        numberInput.min = 0;
        numberInput.placeholder = "人数";

        const countKey = `${instrument}_count`;
        if (postedCounts[countKey]) {
          numberInput.value = postedCounts[countKey];
        }

        div.appendChild(checkbox);
        div.appendChild(label);
        div.appendChild(numberInput);
        percussionContainer.appendChild(div);
      });

      // ===== その他 =====
      const otherContainer = document.getElementById("other-instruments-container");
      otherContainer.innerHTML = "";

      const div = document.createElement("div");
      div.className = "instrument-group";

      const nameInput = document.createElement("input");
      nameInput.type = "text";
      nameInput.name = "other_instrument_name";
      nameInput.placeholder = "楽器名";

      const countInput = document.createElement("input");
      countInput.type = "number";
      countInput.name = "other_instrument_count";
      countInput.min = 0;
      countInput.placeholder = "人数";

      div.appendChild(nameInput);
      div.appendChild(countInput);
      otherContainer.appendChild(div);

      
      // 自由入力欄の保持処理
      const otherNameInput = document.querySelector('input[name="other_instrument_name"]');
      const otherCountInput = document.querySelector('input[name="other_instrument_count"]');

      if (otherNameInput && "<?= $_POST['other_instrument_name'] ?? '' ?>" !== "") {
        otherNameInput.value = "<?= htmlspecialchars($_POST['other_instrument_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>";
      }
      if (otherCountInput && "<?= $_POST['other_instrument_count'] ?? '' ?>" !== "") {
        otherCountInput.value = "<?= htmlspecialchars($_POST['other_instrument_count'] ?? '', ENT_QUOTES, 'UTF-8') ?>";
      }

      // =============================
      //  ✔ チェックボックスと人数欄の連動処理（ONで人数入力可、OFFでゼロリセット）
      // =============================
      function setupInstrumentBehavior() {
        document.querySelectorAll(".instrument-group").forEach(group => {
          const checkbox = group.querySelector('input[type="checkbox"]');
          const numberInput = group.querySelector('input[type="number"]');

          if (!checkbox || !numberInput) return; // 自由入力欄などは除外

          // 初期状態：POST復元でチェックされていたら有効化
          if (checkbox.checked) {
            numberInput.disabled = false;
            numberInput.required = true;
          } else {
            numberInput.disabled = true;
            numberInput.required = false;
          }

          // チェックON/OFFで動的に切り替え
          checkbox.addEventListener("change", function() {
            if (this.checked) {
              numberInput.disabled = false;
              numberInput.required = true;
            } else {
              numberInput.disabled = true;
              numberInput.required = false;
              numberInput.value = ""; // OFFにしたら人数リセット
            }
          });
        });
      }

      // 全ての楽器生成が終わった後に実行
      setupInstrumentBehavior();
    });
  </script>
</body>

</html>