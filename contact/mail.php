<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php //error_reporting(E_ALL | E_STRICT);
##-----------------------------------------------------------------------------------------------------------------##
#
#  PHPメールプログラム　フリー版 ver2.0.4 最終更新日2024/05/24
#　改造や改変は自己責任で行ってください。
#
#  HP: http://www.php-factory.net/
#
#  重要！！サイトでチェックボックスを使用する場合のみですが。。。
#  チェックボックスを使用する場合はinputタグに記述するname属性の値を必ず配列の形にしてください。
#  例　name="当サイトをしったきっかけ[]"  として下さい。
#  nameの値の最後に[と]を付ける。じゃないと複数の値を取得できません！
#
##-----------------------------------------------------------------------------------------------------------------##
if (version_compare(PHP_VERSION, '5.1.0', '>=')) {//PHP5.1.0以上の場合のみタイムゾーンを定義
	date_default_timezone_set('Asia/Tokyo');//タイムゾーンの設定（日本以外の場合には適宜設定ください）
}
/*-------------------------------------------------------------------------------------------------------------------
* ★以下設定時の注意点　
* ・値（=の後）は数字以外の文字列（一部を除く）はダブルクオーテーション「"」、または「'」で囲んでいます。
* ・これをを外したり削除したりしないでください。後ろのセミコロン「;」も削除しないください。
* ・また先頭に「$」が付いた文字列は変更しないでください。数字の1または0で設定しているものは必ず半角数字で設定下さい。
* ・メールアドレスのname属性の値が「Email」ではない場合、以下必須設定箇所の「$Email」の値も変更下さい。
* ・name属性の値に半角スペースは使用できません。
*以上のことを間違えてしまうとプログラムが動作しなくなりますので注意下さい。
-------------------------------------------------------------------------------------------------------------------*/


//---------------------------　必須設定　必ず設定してください　-----------------------

//サイトのトップページのURL　※デフォルトでは送信完了後に「トップページへ戻る」ボタンが表示され、そのリンク先です。
$site_top = "https://zero-carbon-welfare.or.jp/";

//管理者のメールアドレス（送信先） ※メールを受け取るメールアドレス(複数指定する場合は「,」で区切ってください 例 $to = "aa@aa.aa,bb@bb.bb";)
$to = "zero.carbon-welfare@hakuhokaigroup.com";

//送信元（差出人）メールアドレス（管理者宛て、及びユーザー宛メールの送信元（差出人）メールアドレスです）
//必ず実在するメールアドレスでかつ出来る限り設置先サイトのドメインと同じドメインのメールアドレスとしてください（でないと「なりすまし」扱いされます）
//管理者宛てメールの返信先（reply）はユーザーが入力したメールアドレスになりますので返信時はユーザーのメールアドレスが送信先に設定されます）
$from = "send-system@zero-carbon-welfare.or.jp";

//管理者宛メールの送信元（差出人）にユーザーが入力したメールアドレスを表示する(する=1, しない=0)
//ユーザーのメールアドレスを含めることでメーラー上で管理しやすくなる機能です。
//例 example@gmail.com <from@sample.jp>（example@gmail.comがユーザーメールアドレス、from@sample.jpが↑の$fromで設定したメールアドレスです）
$from_add = 1;

//フォームのメールアドレス入力箇所のname属性の値（name="○○"　の○○部分）
$Email = "メールアドレス";

//Reply-To用のメールアドレス
$replyTo = "zero.carbon-welfare@hakuhokaigroup.com";
//---------------------------　必須設定　ここまで　------------------------------------


//---------------------------　セキュリティ、スパム防止のための設定　------------------------------------

//スパム防止のためのリファラチェック（フォーム側とこのファイルが同一ドメインであるかどうかのチェック）(する=1, しない=0)
//※有効にするにはこのファイルとフォームのページが同一ドメイン内にある必要があります
$Referer_check = 0;

//リファラチェックを「する」場合のドメイン ※設置するサイトのドメインを指定して下さい。
//もしこの設定が間違っている場合は送信テストですぐに気付けます。
$Referer_check_domain = "zero-carbon-welfare.or.jp";

/*セッションによるワンタイムトークン（CSRF対策、及びスパム防止）(する=1, しない=0)
※ただし、この機能を使う場合は↓の送信確認画面の表示が必須です。（デフォルトではON（1）になっています）
※【重要】ガラケーは機種によってはクッキーが使えないためガラケーの利用も想定してる場合は「0」（OFF）にして下さい（PC、スマホは問題ないです）*/
$useToken = 1;
//---------------------------　セキュリティ、スパム防止のための設定　ここまで　------------------------------------


//---------------------- 任意設定　以下は必要に応じて設定してください ------------------------

// Bccで送るメールアドレス(複数指定する場合は「,」で区切ってください 例 $BccMail = "aa@aa.aa,bb@bb.bb";)
$BccMail = "ds-maildebug@sunpla.com";

// 管理者宛に送信されるメールのタイトル（件名）
$subject = "様より協力・協賛のお問い合わせを賜りました｜ゼロ・カーボンHP";

// 送信確認画面の表示(する=1, しない=0)
$confirmDsp = 1;

// 送信完了後に自動的に指定のページ(サンクスページなど)に移動する(する=1, しない=0)
// CV率を解析したい場合などはサンクスページを別途用意し、URLをこの下の項目で指定してください。
// 0にすると、デフォルトの送信完了画面が表示されます。
$jumpPage = 1;

// 送信完了後に表示するページURL（上記で1を設定した場合のみ）※httpから始まるURLで指定ください。（相対パスでも基本的には問題ないです）
$thanksPage = "thanks.php";

// 必須入力項目を設定する(する=1, しない=0)
$requireCheck = 1;

/* 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
値はシングルクォーテーションで囲み、複数の場合はカンマで区切ってください。フォーム側と順番を合わせると良いです。
配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。*/
$require = array('御社名','事業内容','企業サイトURL','ご担当者名','ふりがな','電話番号','メールアドレス');


//----------------------------------------------------------------------
//  自動返信メール設定(START)
//----------------------------------------------------------------------

// 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
// 送る場合は、フォーム側のメール入力欄のname属性の値が上記「$Email」で指定した値と同じである必要があります
$remail = 1;

//自動返信メールの送信者欄に表示される名前　※あなたの名前や会社名など（もし自動返信メールの送信者名が文字化けする場合ここは空にしてください）
$refrom_name = "一般社団法人日本ゼロカーボン・ウェルフェア協議会";

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$re_subject = "【協力・協賛】お問い合わせいただき有難うございます";

//フォーム側の「名前」箇所のname属性の値　※自動返信メールの「○○様」の表示で使用します。
//指定しない、または存在しない場合は、○○様と表示されないだけです。あえて無効にしてもOK
$dsp_name = 'ご担当者名';

//自動返信メールの冒頭の文言 ※日本語部分のみ変更可
$remail_text = <<< TEXT

--- 自動返信メール ---

この度は、当協議会の取り組みにご関心をお持ちいただきまして誠にありがとうございます。
お送りいただきました内容を確認の上、担当者より折り返しご連絡させていただきます。

TEXT;


//自動返信メールに署名（フッター）を表示(する=1, しない=0)※管理者宛にも表示されます。
$mailFooterDsp = 1;

//上記で「1」を選択時に表示する署名（フッター）（FOOTER～FOOTER;の間に記述してください）
$mailSignature = <<< FOOTER

-----------------------------------------------------------
一般社団法人日本ゼロカーボン・ウェルフェア協議会

〒678-0239
兵庫県赤穂市加里屋98番地16
https://zero-carbon-welfare.or.jp
-----------------------------------------------------------
※本メールは送信専用メールアドレスから配信されております。
※返信でのお問い合わせは承りかねますので、あらかじめご了承願います。

FOOTER;


//----------------------------------------------------------------------
//  自動返信メール設定(END)
//----------------------------------------------------------------------

//メールアドレスの形式チェックを行うかどうか。(する=1, しない=0)
//※デフォルトは「する」。特に理由がなければ変更しないで下さい。メール入力欄のname属性の値が上記「$Email」で指定した値である必要があります。
$mail_check = 1;

//全角英数字→半角変換を行うかどうか。(する=1, しない=0)
$hankaku = 1;

//全角英数字→半角変換を行う項目のname属性の値（name="○○"の「○○」部分）
//※複数の場合にはカンマで区切って下さい。（上記で「1」を指定した場合のみ有効）
//配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。
$hankaku_array = array('電話番号','サイトURL');

//-fオプションによるエンベロープFrom（Return-Path）の設定(する=1, しない=0)　
//※宛先不明（間違いなどで存在しないアドレス）の場合に 管理者宛に「Mail Delivery System」から「Undelivered Mail Returned to Sender」というメールが届きます。
//サーバーによっては稀にこの設定が必須の場合もあります。
//設置サーバーでPHPがセーフモードで動作している場合は使用できませんので送信時にエラーが出たりメールが届かない場合は「0」（OFF）として下さい。
$use_envelope = 1;

//機種依存文字の変換
/*たとえば㈱（かっこ株）や①（丸1）、その他特殊な記号や特殊な漢字などは変換できずに「？」と表示されます。それを回避するための機能です。
確認画面表示時に置換処理されます。「変換前の文字」が「変換後の文字」に変換され、送信メール内でも変換された状態で送信されます。（たとえば「㈱」の場合、「（株）」に変換されます）
必要に応じて自由に追加して下さい。ただし、変換前の文字と変換後の文字の順番と数は必ず合わせる必要がありますのでご注意下さい。*/

//変換前の文字
$replaceStr['before'] = array('①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','№','㈲','㈱','髙');
//変換後の文字
$replaceStr['after'] = array('(1)','(2)','(3)','(4)','(5)','(6)','(7)','(8)','(9)','(10)','No.','（有）','（株）','高');

//------------------------------- 任意設定ここまで ---------------------------------------------


// 以下の変更は知識のある方のみ自己責任でお願いします。

//----------------------------------------------------------------------
//  関数実行、変数初期化
//----------------------------------------------------------------------
//トークンチェック用のセッションスタート
if($useToken == 1 && $confirmDsp == 1){
	session_name('PHPMAILFORMSYSTEM');
	session_start();
}
$encode = "UTF-8";//このファイルの文字コード定義（変更不可）
if(isset($_GET)) $_GET = sanitize($_GET);//NULLバイト除去//
if(isset($_POST)) $_POST = sanitize($_POST);//NULLバイト除去//
if(isset($_COOKIE)) $_COOKIE = sanitize($_COOKIE);//NULLバイト除去//
if($encode == 'SJIS') $_POST = sjisReplace($_POST,$encode);//Shift-JISの場合に誤変換文字の置換実行
$funcRefererCheck = refererCheck($Referer_check,$Referer_check_domain);//リファラチェック実行

//変数初期化
$sendmail = 0;
$empty_flag = 0;
$post_mail = '';
$errm ='';
$header ='';

if($requireCheck == 1) {
	$requireResArray = requireCheck($require);//必須チェック実行し返り値を受け取る
	$errm = $requireResArray['errm'];
	$empty_flag = $requireResArray['empty_flag'];
}
//メールアドレスチェック
if(empty($errm)){
	foreach($_POST as $key=>$val) {
		if($val == "confirm_submit") $sendmail = 1;
		if($key == $Email) $post_mail = h($val);
		if($key == $Email && $mail_check == 1 && !empty($val)){
			if(!checkMail($val)){
				$errm .= "<p class=\"error_messe\">【".$key."】はメールアドレスの形式が正しくありません。</p>\n";
				$empty_flag = 1;
			}
		}
	}
}

if(($confirmDsp == 0 || $sendmail == 1) && $empty_flag != 1){

	//トークンチェック（CSRF対策）※確認画面がONの場合のみ実施
	if($useToken == 1 && $confirmDsp == 1){
		if(empty($_SESSION['mailform_token']) || ($_SESSION['mailform_token'] !== $_POST['mailform_token'])){
			exit('ページ遷移が不正です');
		}
		if(isset($_SESSION['mailform_token'])) unset($_SESSION['mailform_token']);//トークン破棄
		if(isset($_POST['mailform_token'])) unset($_POST['mailform_token']);//トークン破棄
	}

	//差出人に届くメールをセット
	if($remail == 1) {
		$userBody = mailToUser($_POST,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode);
		$reheader = userHeader($refrom_name,$from,$encode);
		$re_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($re_subject,"JIS",$encode))."?=";
	}
	//管理者宛に届くメールをセット
  $subject = $_POST['ご担当者名'].' '.$subject;

	$adminBody = mailToAdmin($_POST,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp);
	$header = adminHeader($post_mail,$BccMail);
	$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"JIS",$encode))."?=";

	//-fオプションによるエンベロープFrom（Return-Path）の設定(safe_modeがOFFの場合かつ上記設定がONの場合のみ実施)
	if($use_envelope == 0){
		mail($to,$subject,$adminBody,$header);
		if($remail == 1 && !empty($post_mail)) mail($post_mail,$re_subject,$userBody,$reheader);
	}else{
		// mail($to_sub,$subject,$adminBody,$header,'-f'.$from);
		mail($to,$subject,$adminBody,$header,'-f'.$from);
		if($remail == 1 && !empty($post_mail)) mail($post_mail,$re_subject,$userBody,$reheader,'-f'.$from);
	}
}
else if($confirmDsp == 1){

/*　▼▼▼送信確認画面のレイアウト※編集可　オリジナルのデザインも適用可能▼▼▼　*/
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <link rel="icon" href="/favicon.png"> -->
    <!-- Google Tag Manager -->
    <script>
      (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
        var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s),
          dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', 'GTM-NDQG4K3B');
    </script>
    <!-- End Google Tag Manager -->
    <script>
      (function (d) {
        var config = {
            kitId: 'mfj0cwi',
            scriptTimeout: 3000,
            async: true,
          },
          h = d.documentElement,
          t = setTimeout(function () {
            h.className =
              h.className.replace(/\bwf-loading\b/g, '') + ' wf-inactive';
          }, config.scriptTimeout),
          tk = d.createElement('script'),
          f = false,
          s = d.getElementsByTagName('script')[0],
          a;
        h.className += ' wf-loading';
        tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
        tk.async = true;
        tk.onload = tk.onreadystatechange = function () {
          a = this.readyState;
          if (f || (a && a != 'complete' && a != 'loaded')) return;
          f = true;
          clearTimeout(t);
          try {
            Typekit.load(config);
          } catch (e) {}
        };
        s.parentNode.insertBefore(tk, s);
      })(document);
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Benne&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <title>
      お問い合わせ確認画面｜一般社団法人日本ゼロカーボン・ウェルフェア協議会
    </title>
    <script
      defer="defer"
      src="./assets/js/contactbundle.js?79d3b883fa7e2f1861e1"
    ></script>
    <link href="assets/css/style.css?79d3b883fa7e2f1861e1" rel="stylesheet" />
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript
      ><iframe
        src="https://www.googletagmanager.com/ns.html?id=GTM-NDQG4K3B"
        height="0"
        width="0"
        style="display: none; visibility: hidden"
      ></iframe
    ></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="l-siteWrapper">
      <header class="l-header is-main">
        <div class="l-headerWrapper">
          <div class="l-headerLogo">
            <a href="/">
              <img
                src="assets/images/logo.svg"
                alt="一般社団法人日本ゼロカーボン・ウェルフェア協議会"
                width="270"
                height="22"
              />
            </a>
          </div>
          <div class="l-headerCtaWrap">
            <a href="/contact/" class="l-headerCtaButton">
              <span class="l-headerCtaButtonText">
                協力・協賛に関するお問い合わせ
              </span>
              <img
                class="l-headerCtaButtonIcon"
                src="assets/images/arrow_right_alt.svg"
                alt=""
                width="26"
                height="11"
              />
              <img
                class="l-headerCtaButtonIcon-w"
                src="assets/images/arrow_right_alt_w.svg"
                alt=""
                width="26"
                height="11"
              />
            </a>
            <button
              class="l-headerNavigationButton"
              type="button"
              data-js="true"
            >
              <span class="l-headerNavigationButtonLine"></span>
            </button>
          </div>
          <nav class="l-headerNav" data-js="true">
            <ul class="l-headerNavList">
              <li class="l-headerNavListItem">
                <a href="/#news" class="l-headerNavListItemLink">お知らせ</a>
              </li>
              <li class="l-headerNavListItem">
                <a href="/#aboutus" class="l-headerNavListItemLink"
                  >私たちについて</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#info" class="l-headerNavListItemLink">基本情報</a>
              </li>
              <li class="l-headerNavListItem">
                <a href="/#greeting" class="l-headerNavListItemLink"
                  >代表理事ごあいさつ</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#about" class="l-headerNavListItemLink"
                  >医療・介護業界と気候変動について</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#case" class="l-headerNavListItemLink"
                  >発起人の取り組み事例</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#group" class="l-headerNavListItemLink"
                  >伯鳳会グループについて</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/contact/" class="l-headerNavListItemLink"
                  >お問い合わせ</a
                >
              </li>
            </ul>
          </nav>
        </div>
      </header>

<!-- ▲ Headerやその他コンテンツなど　※自由に編集可 ▲-->

<!-- ▼************ 送信内容表示部　※編集は自己責任で ************ ▼-->
<div id="formWrap">
<?php if($empty_flag == 1){ ?>
<main class="l-main">
  <section class="p-contact">
    <div class="l-wrapper l-wrapper--s p-contactWrapper">
      <div class="l-sectionTitleWrap">
        <h2 class="l-sectionTitle">
          <span class="l-sectionTitleText">お問い合わせ</span>
          <span class="l-sectionTitleSub u-uppercase" lang="en"
            >Contact</span
          >
        </h2>
      </div>
      <div class="p-contactContentHead">
        <div class="p-contactContentHeadText">
          <p>
            入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。
          </p>
        </div>
      </div>
      <div class="p-contactContentBody">
        <div action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="POST" class="p-contactContentForm">
          <?php echo $errm; ?>
          <p class="p-contactContentForm-button u-centerposition">
            <input class="p-contactContentForm-submit p-contactContentForm-submit--back" type="button" value="入力画面に戻る" onClick="history.back()">
          </p>
        </div>
      </div>
    </div>
  </section>
</main>
<?php }else{ ?>
<main class="l-main">
  <section class="p-contact p-contact--confirm">
    <div class="l-wrapper l-wrapper--s p-contactWrapper">
      <div class="l-sectionTitleWrap">
        <h2 class="l-sectionTitle">
          <span class="l-sectionTitleText">お問い合わせ</span>
          <span class="l-sectionTitleSub u-uppercase" lang="en"
            >Contact</span
          >
        </h2>
      </div>
      <div class="p-contactContentHead">
        <div class="p-contactContentHeadText">
          <p>
            当協議会の取り組みにご関心をお持ちいただきまして、ありがとうございます。<br />私たちと一緒に医療・介護業界およびその他関係業界のゼロ―カーボンの支援を通して、脱炭素社会の実現に向け積極的に活動いただける企業や団体のみなさまを募集しております。<br />ご興味がございましたら下記よりお問い合わせをお願いいたします。
          </p>
        </div>
        <div class="p-contactContentJoinText">
          <p>
            <a
              href="https://docs.google.com/forms/d/e/1FAIpQLScr7-6dEPinSNKdvDjAXqr4tnBHNgcaEXGYI3p_Rm7ds5ChNw/viewform"
              target="_blank"
              >入会はこちらからもお申込いただけます</a
            >
          </p>
        </div>
        <div class="p-contactContentHeadAlert">
          <p>印は必ずご入力ください。数字は半角入力でお願い致します。</p>
        </div>
      </div>
      <div class="p-contactContentBody">
        <form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="POST" class="p-contactContentForm">
          <dl class="p-contactContentForm-list">
            <?php echo confirmOutput($_POST);//入力内容を表示?>
          </dl>
          <input type="hidden" name="mail_set" value="confirm_submit">
          <input type="hidden" name="httpReferer" value="<?php echo h($_SERVER['HTTP_REFERER']);?>">
          <p class="p-contactContentForm-button u-centerposition">
            <input class="p-contactContentForm-submit p-contactContentForm-submit--back" type="button" value="入力画面に戻る" onClick="history.back()">
            <input class="p-contactContentForm-submit" type="submit" value="送信する" />
          </p>
        </form>
      </div>
    </div>
  </section>
</main>
<?php } ?>
</div><!-- /formWrap -->
<!-- ▲ *********** 送信内容確認部　※編集は自己責任で ************ ▲-->

<!-- ▼ Footerその他コンテンツなど　※編集可 ▼-->
  <footer class="l-footer">
    <div class="l-footerWrapper">
      <div class="l-footerInner">
        <div class="l-footerHead">
          <div class="l-footerHeadWrap">
            <h2 class="l-footerHeadTitle">
              一般社団法人<br class="u-spOnly"/>日本ゼロカーボン・ウェルフェア協議会
              <span class="l-footerHeadTitleSub" lang="en"
                >The Japan Zero Carbon Welfare General Incorporated
                Association</span
              >
            </h2>
          </div>
          <div class="l-footerContent">
            <div class="l-footerContentItem">
              <h3 class="l-footerContentItemTitle">所在地</h3>
              <p class="l-footerContentItemText">
                〒678-0239　兵庫県赤穂市加里屋98番地16
              </p>
            </div>
            <div class="l-footerContentItem">
              <h3 class="l-footerContentItemTitle">連絡先</h3>
              <p class="l-footerContentItemText"><a href="mailto:zero.carbon-welfare@hakuhokaigroup.com?subject=貴法人に関するお問い合わせ">櫻井宛：zero.carbon-welfare@hakuhokaigroup.com</a></p>
            </div>
            <div class="l-footerContentItem">
              <h3 class="l-footerContentItemTitle">共同設立</h3>
              <p class="l-footerContentItemText">
                伯鳳会グループ（理事長：古城資久）<br />
                株式会社UPDATER（代表取締役：大石英司）<br />
                医療法人社団永生会（理事長：安藤高夫）<br />
                湖山医療福祉グループ（代表：湖山泰成）<br />
                社会医療法人石川記念会 HITO病院（理事長：石川賀代）<br />
                社会医療法人耳鼻咽喉科麻生病院（理事長：大橋正實）
              </p>
            </div>
            <div class="l-footerContentArticle">
              <p><a href="/assets/pdf/articles.pdf" target="_blank">一般社団法人日本ゼロカーボン・ウェルフェア協議会・定款</a></p>
            </div>
          </div>
        </div>
        <div class="l-footerBody">
          <div class="l-frame l-footerFrame">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3277.977483739826!2d134.38472510314455!3d34.756160012833085!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35545f43b7264715%3A0xea1e5c8c44bfb4c8!2z44CSNjc4LTAyMzkg5YW15bqr55yM6LWk56mC5biC5Yqg6YeM5bGL77yZ77yY4oiS77yR77yW!5e0!3m2!1sja!2sjp!4v1726375053371!5m2!1sja!2sjp"
              width="600"
              height="450"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
      <div class="l-footerFoot">
        <p class="l-footerCopy">
          <small
            >©2024 The Japan Zero Carbon Welfare <br />General Incorporated
            Associationl.All rights reserved.</small
          >
        </p>
      </div>
    </div>
  </footer>
</div>
</body>
</html>
<?php
/* ▲▲▲送信確認画面のレイアウト　※オリジナルのデザインも適用可能▲▲▲　*/
}

if(($jumpPage == 0 && $sendmail == 1) || ($jumpPage == 0 && ($confirmDsp == 0 && $sendmail == 0))) {

/* ▼▼▼送信完了画面のレイアウト　編集可 ※送信完了後に指定のページに移動しない場合のみ表示▼▼▼　*/
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <link rel="icon" href="/favicon.png"> -->
    <!-- Google Tag Manager -->
    <script>
      (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
        var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s),
          dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
      })(window, document, 'script', 'dataLayer', 'GTM-NDQG4K3B');
    </script>
    <!-- End Google Tag Manager -->
    <script>
      (function (d) {
        var config = {
            kitId: 'mfj0cwi',
            scriptTimeout: 3000,
            async: true,
          },
          h = d.documentElement,
          t = setTimeout(function () {
            h.className =
              h.className.replace(/\bwf-loading\b/g, '') + ' wf-inactive';
          }, config.scriptTimeout),
          tk = d.createElement('script'),
          f = false,
          s = d.getElementsByTagName('script')[0],
          a;
        h.className += ' wf-loading';
        tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
        tk.async = true;
        tk.onload = tk.onreadystatechange = function () {
          a = this.readyState;
          if (f || (a && a != 'complete' && a != 'loaded')) return;
          f = true;
          clearTimeout(t);
          try {
            Typekit.load(config);
          } catch (e) {}
        };
        s.parentNode.insertBefore(tk, s);
      })(document);
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Benne&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
      rel="stylesheet"
    />
    <title>
      お問い合わせ確認画面｜一般社団法人日本ゼロカーボン・ウェルフェア協議会
    </title>
    <script
      defer="defer"
      src="./assets/js/contactbundle.js?79d3b883fa7e2f1861e1"
    ></script>
    <link href="assets/css/style.css?79d3b883fa7e2f1861e1" rel="stylesheet" />
  </head>
  <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript
      ><iframe
        src="https://www.googletagmanager.com/ns.html?id=GTM-NDQG4K3B"
        height="0"
        width="0"
        style="display: none; visibility: hidden"
      ></iframe
    ></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="l-siteWrapper">
      <header class="l-header is-main">
        <div class="l-headerWrapper">
          <div class="l-headerLogo">
            <a href="/">
              <img
                src="assets/images/logo.svg"
                alt="一般社団法人日本ゼロカーボン・ウェルフェア協議会"
                width="270"
                height="22"
              />
            </a>
          </div>
          <div class="l-headerCtaWrap">
            <a href="/contact/" class="l-headerCtaButton">
              <span class="l-headerCtaButtonText">
                協力・協賛に関するお問い合わせ
              </span>
              <img
                class="l-headerCtaButtonIcon"
                src="assets/images/arrow_right_alt.svg"
                alt=""
                width="26"
                height="11"
              />
              <img
                class="l-headerCtaButtonIcon-w"
                src="assets/images/arrow_right_alt_w.svg"
                alt=""
                width="26"
                height="11"
              />
            </a>
            <button
              class="l-headerNavigationButton"
              type="button"
              data-js="true"
            >
              <span class="l-headerNavigationButtonLine"></span>
            </button>
          </div>
          <nav class="l-headerNav" data-js="true">
            <ul class="l-headerNavList">
              <li class="l-headerNavListItem">
                <a href="/#news" class="l-headerNavListItemLink">お知らせ</a>
              </li>
              <li class="l-headerNavListItem">
                <a href="/#aboutus" class="l-headerNavListItemLink"
                  >私たちについて</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#info" class="l-headerNavListItemLink">基本情報</a>
              </li>
              <li class="l-headerNavListItem">
                <a href="/#greeting" class="l-headerNavListItemLink"
                  >理事長ごあいさつ</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#about" class="l-headerNavListItemLink"
                  >医療・介護業界と気候変動について</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#case" class="l-headerNavListItemLink"
                  >発起人の取り組み事例</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/#group" class="l-headerNavListItemLink"
                  >伯鳳会グループについて</a
                >
              </li>
              <li class="l-headerNavListItem">
                <a href="/contact/" class="l-headerNavListItemLink"
                  >お問い合わせ</a
                >
              </li>
            </ul>
          </nav>
        </div>
      </header>
<div>
<?php if($empty_flag == 1){ ?>
<h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
<div style="color:red"><?php echo $errm; ?></div>
<br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()">
</div>
</body>
</html>
<?php }else{ ?>
<main class="l-main">
  <section class="p-contact p-contact--thanks">
    <div class="l-wrapper l-wrapper--s p-contactWrapper">
      <div class="l-sectionTitleWrap">
        <h2 class="l-sectionTitle">
          <span class="l-sectionTitleText">お問い合わせ</span>
          <span class="l-sectionTitleSub u-uppercase" lang="en"
            >Contact</span
          >
        </h2>
      </div>
      <div class="p-contactContentHead">
        <div class="p-contactContentHeadText">
          <p>
            当協議会の取り組みにご関心をお持ちいただきまして、ありがとうございます。お送りいただきました内容を確認の上、担当より折り返しご連絡させていただきます。（ご入力のメールアドレスへ自動返信の確認メールをお送りしております）<br><br>しばらく経ってもメールが届かない場合は、ご入力のメールアドレスが間違っているか、迷惑メールフォルダに振り分けられている可能性がございます。お手数ですがもう一度フォームよりお問い合わせいただきますようお願い申し上げます。
          </p>
        </div>
      </div>
      <div class="p-contactContentBody">
        <div class="p-contactContentForm-button u-centerposition">
          <a class="p-contactContentForm-submit" href="<?php echo $site_top ;?>">トップに戻る</a>
        </div>
      </div>
    </div>
  </section>
</main>
<!--  CV率を計測する場合ここにAnalyticsコードを貼り付け -->
  <footer class="l-footer">
    <div class="l-footerWrapper">
      <div class="l-footerInner">
        <div class="l-footerHead">
          <div class="l-footerHeadWrap">
            <h2 class="l-footerHeadTitle">
              一般社団法人<br class="u-spOnly"/>日本ゼロカーボン・ウェルフェア協議会
              <span class="l-footerHeadTitleSub" lang="en"
                >The Japan Zero Carbon Welfare General Incorporated
                Association</span
              >
            </h2>
          </div>
          <div class="l-footerContent">
            <div class="l-footerContentItem">
              <h3 class="l-footerContentItemTitle">所在地</h3>
              <p class="l-footerContentItemText">
                〒678-0239　兵庫県赤穂市加里屋98番地16
              </p>
            </div>
            <div class="l-footerContentItem">
              <h3 class="l-footerContentItemTitle">連絡先</h3>
              <p class="l-footerContentItemText"><a href="mailto:zero.carbon-welfare@hakuhokaigroup.com?subject=貴法人に関するお問い合わせ">櫻井宛：zero.carbon-welfare@hakuhokaigroup.com</a></p>
            </div>
            <div class="l-footerContentItem">
              <h3 class="l-footerContentItemTitle">共同設立</h3>
              <p class="l-footerContentItemText">
                伯鳳会グループ（理事長：古城資久）<br />
                株式会社UPDATER（代表取締役：大石英司）<br />
                医療法人社団永生会（理事長：安藤高夫）<br />
                湖山医療福祉グループ（代表：湖山泰成）<br />
                社会医療法人石川記念会 HITO病院（理事長：石川賀代）<br />
                社会医療法人耳鼻咽喉科麻生病院（理事長：大橋正實）
              </p>
            </div>
            <div class="l-footerContentArticle">
              <p><a href="/assets/pdf/articles.pdf" target="_blank">一般社団法人日本ゼロカーボン・ウェルフェア協議会・定款</a></p>
            </div>
          </div>
        </div>
        <div class="l-footerBody">
          <div class="l-frame l-footerFrame">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3277.977483739826!2d134.38472510314455!3d34.756160012833085!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35545f43b7264715%3A0xea1e5c8c44bfb4c8!2z44CSNjc4LTAyMzkg5YW15bqr55yM6LWk56mC5biC5Yqg6YeM5bGL77yZ77yY4oiS77yR77yW!5e0!3m2!1sja!2sjp!4v1726375053371!5m2!1sja!2sjp"
              width="600"
              height="450"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
      <div class="l-footerFoot">
        <p class="l-footerCopy">
          <small
            >©2024 The Japan Zero Carbon Welfare <br />General Incorporated
            Associationl.All rights reserved.</small
          >
        </p>
      </div>
    </div>
  </footer>
</div>
</body>
</html>
<?php
/* ▲▲▲送信完了画面のレイアウト 編集可 ※送信完了後に指定のページに移動しない場合のみ表示▲▲▲　*/
  }
}
//確認画面無しの場合の表示、指定のページに移動する設定の場合、エラーチェックで問題が無ければ指定ページヘリダイレクト
else if(($jumpPage == 1 && $sendmail == 1) || $confirmDsp == 0) {
	if($empty_flag == 1){ ?>
<div><h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4><div style="color:red"><?php echo $errm; ?></div><br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()"></div>
<?php
	}else{ header("Location: ".$thanksPage); }
}

// 以下の変更は知識のある方のみ自己責任でお願いします。

//----------------------------------------------------------------------
//  関数定義(START)
//----------------------------------------------------------------------
function checkMail($str){
	$mailaddress_array = explode('@',$str);
	if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-zA-Z]+(\.[!#%&\-_0-9a-zA-Z]+)+$/", "$str") && count($mailaddress_array) ==2){
		return true;
	}else{
		return false;
	}
}
function h($string) {
	global $encode;
	return htmlspecialchars($string, ENT_QUOTES,$encode);
}
function sanitize($arr){
	if(is_array($arr)){
		return array_map('sanitize',$arr);
	}
	return str_replace("\0","",$arr);
}
//Shift-JISの場合に誤変換文字の置換関数
function sjisReplace($arr,$encode){
	foreach($arr as $key => $val){
		$key = str_replace('＼','ー',$key);
		$resArray[$key] = $val;
	}
	return $resArray;
}
//送信メールにPOSTデータをセットする関数
function postToMail($arr){
	global $hankaku,$hankaku_array;
	$resArray = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){
				//連結項目の処理
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');

		}else{ $out = $val; }//チェックボックス（配列）追記ここまで

		if (version_compare(PHP_VERSION, '5.1.0', '<=')) {//PHP5.1.0以下の場合のみ実行（7.4でget_magic_quotes_gpcが非推奨になったため）
			if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		}

		//全角→半角変換
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		if($out != "confirm_submit" && $key != "httpReferer") {
			$resArray .= "■".h($key)."\n".h($out)."\n\n";
		}
	}
	return $resArray;
}
//確認画面の入力内容出力用関数
function confirmOutput($arr){
	global $hankaku,$hankaku_array,$useToken,$confirmDsp,$replaceStr;
	$html = '';
	foreach($arr as $key => $val) {
		$out = '';
		if (is_array($val)) {
			$count = count($val);
			$index = 0;
			foreach ($val as $key02 => $item) {
				//連結項目の処理
				if (is_array($item)) {
					$out .= connect2val($item);
				} else {
					$out .= $item;
					if ($index < $count - 1) {
						$out .= "\n";
					}
				}
				$index++;
			}
			$out = rtrim($out,', ');

		}else{ $out = $val; }//チェックボックス（配列）追記ここまで

		if (version_compare(PHP_VERSION, '5.1.0', '<=')) {//PHP5.1.0以下の場合のみ実行（7.4でget_magic_quotes_gpcが非推奨になったため）
			if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		}

		//全角→半角変換
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}

		$out = nl2br(h($out));//※追記 改行コードを<br>タグに変換
		$key = h($key);
		$out = str_replace($replaceStr['before'], $replaceStr['after'], $out);//機種依存文字の置換処理

		$html .= "<div class=\"p-contactContentForm-item\"><dt class=\"p-contactContentForm-title\">".$key."</dt><dd class=\"p-contactContentForm-detail\">".$out;
		$html .= '<input type="hidden" name="'.$key.'" value="'.str_replace(array("<br />","<br>"),"",$out).'" />';
		$html .= "</div>\n";
	}
	//トークンをセット
	if($useToken == 1 && $confirmDsp == 1){
		$token = sha1(uniqid(mt_rand(), true));
		$_SESSION['mailform_token'] = $token;
		$html .= '<input type="hidden" name="mailform_token" value="'.$token.'" />';
	}

	return $html;
}

//全角→半角変換
function zenkaku2hankaku($key,$out,$hankaku_array){
	global $encode;
	if(is_array($hankaku_array) && function_exists('mb_convert_kana')){
		foreach($hankaku_array as $hankaku_array_val){
			if($key == $hankaku_array_val){
				$out = mb_convert_kana($out,'a',$encode);
			}
		}
	}
	return $out;
}
//配列連結の処理
function connect2val($arr){
	$out = '';
	foreach($arr as $key => $val){
		if($key === 0 || $val == ''){//配列が未記入（0）、または内容が空のの場合には連結文字を付加しない（型まで調べる必要あり）
			$key = '';
		}elseif(strpos($key,"円") !== false && $val != '' && preg_match("/^[0-9]+$/",$val)){
			$val = number_format($val);//金額の場合には3桁ごとにカンマを追加
		}
		$out .= $val . $key;
	}
	return $out;
}

//管理者宛送信メールヘッダ
function adminHeader($post_mail, $BccMail){
  global $from, $from_add;
  $header = "From: ";
  if(!empty($_POST['御社名']) && $from_add == 1){
      $header .= mb_encode_mimeheader($_POST['御社名'])." <".$from.">\n";
  }else{
      $header .= $from."\n";
  }
  if($BccMail != '') {
      $header .= "Bcc: $BccMail\n";
  }
  if(!empty($post_mail)) {
      $header .= "Reply-To: ".$post_mail."\n";
  }
  $header .= "Content-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
  return $header;
}
//管理者宛送信メールボディ
function mailToAdmin($arr,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp){
	$adminBody="--- 管理者様宛メール ---\n\n";
	$adminBody .=$_POST['御社名'].' '.$_POST['ご担当者名']."様よりお問い合わせを賜りました。\n";
	$adminBody .="ご確認・ご対応をお願い致します。\n\nお問い合わせ元サイト\nhttps://zero-carbon-welfare.or.jp"."\n\n";
	$adminBody .="-------------------------------------------------\nお問い合わせ内容\n";
	$adminBody .="-------------------------------------------------\n\n";
	$adminBody.= postToMail($arr);//POSTデータを関数からセット
	$adminBody.="\n-------------------------------------------------\n";
	$adminBody.="※機種依存文字を入力された場合、\n送信内容が正常に反映されない可能性があります。";
	$adminBody.="\n-------------------------------------------------\n";
	// $adminBody.="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	// $adminBody.="送信者のIPアドレス：".@$_SERVER["REMOTE_ADDR"]."\n";
	// $adminBody.="送信者のホスト名：".getHostByAddr(getenv('REMOTE_ADDR'))."\n";
	// if($confirmDsp != 1){
	// 	$adminBody.="問い合わせのページURL：".@$_SERVER['HTTP_REFERER']."\n";
	// }else{
	// 	$adminBody.="問い合わせのページURL：".@$arr['httpReferer']."\n";
	// }
	// if($mailFooterDsp == 1) $adminBody.= $mailSignature;
	return mb_convert_encoding($adminBody,"JIS",$encode);
}

//ユーザ宛送信メールヘッダ
function userHeader($refrom_name,$to,$encode){
  global $replyTo;  // 新しく追加した定数を使用
  $reheader = "From: ";
  if(!empty($refrom_name)){
      $default_internal_encode = mb_internal_encoding();
      if($default_internal_encode != $encode){
          mb_internal_encoding($encode);
      }
      $reheader .= mb_encode_mimeheader($refrom_name)." <".$to.">\nReply-To: ".$replyTo;
  }else{
      $reheader .= "$to\nReply-To: ".$replyTo;
  }
  $reheader .= "\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
  return $reheader;
}
//ユーザ宛送信メールボディ
function mailToUser($arr,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode){
	$userBody = '';
	if(isset($arr[$dsp_name])) $userBody = h($arr[$dsp_name]). " 様\n";
	$userBody.= $remail_text;
	$userBody.="\n-------------------------------------------------\nお問い合わせ内容\n";
	$userBody .="-------------------------------------------------\n\n";
	$userBody.= postToMail($arr);//POSTデータを関数からセット
	// $userBody.="\n-------------------------------------------------\n\n";
	// $userBody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	if($mailFooterDsp == 1) $userBody.= $mailSignature;
	return mb_convert_encoding($userBody,"JIS",$encode);
}
//必須チェック関数
function requireCheck($require){
	$res['errm'] = '';
	$res['empty_flag'] = 0;
	foreach($require as $requireVal){
		$existsFalg = '';
		foreach($_POST as $key => $val) {
			if($key == $requireVal) {

				//連結指定の項目（配列）のための必須チェック
				if(is_array($val)){
					$connectEmpty = 0;
					foreach($val as $kk => $vv){
						if(is_array($vv)){
							foreach($vv as $kk02 => $vv02){
								if($vv02 == ''){
									$connectEmpty++;
								}
							}
						}

					}
					if($connectEmpty > 0){
						$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
						$res['empty_flag'] = 1;
					}
				}
				//デフォルト必須チェック
				elseif($val == ''){
					$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
					$res['empty_flag'] = 1;
				}

				$existsFalg = 1;
				break;
			}

		}
		if($existsFalg != 1){
				$res['errm'] .= "<p class=\"error_messe\">【".$requireVal."】が未選択です。</p>\n";
				$res['empty_flag'] = 1;
		}
	}

	return $res;
}
//リファラチェック
function refererCheck($Referer_check,$Referer_check_domain){
	if($Referer_check == 1 && !empty($Referer_check_domain)){
		if(strpos($_SERVER['HTTP_REFERER'],$Referer_check_domain) === false){
			return exit('<p>リファラチェックエラー。フォームページのドメインとこのファイルのドメインが一致しません</p>');
		}
	}
}
//----------------------------------------------------------------------
//  関数定義(END)
//----------------------------------------------------------------------
?>
