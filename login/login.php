<?php
include("../language.php");
require_once('../authorize/mastodon.php');
use HalcyonSuite\HalcyonForMastodon\Mastodon;
if (isset($_POST['acct'])) {
$domain = explode("@", mb_strtolower(htmlspecialchars((string)filter_input(INPUT_POST, 'acct'), ENT_QUOTES)))[2];
$URL= 'https://'.$domain;
$api= new Mastodon();
if(!preg_match('/(^[a-z0-9\-\.\/]+?\.[a-z0-9-]+$)/',$domain) || in_array($domain,json_decode(base64_decode("WyJnYWIuY29tIiwiZ2FiLmFpIl0=")))) {
header('Location: '.$api->clientWebsite.'/login?cause=domain', true, 303);
die();
} else {
try {
$client_id = $api->getInstance($URL)["client_id"];
$authorizeURL= $URL.'/oauth/authorize?client_id='.$client_id.'&response_type=code&scope=read+write+follow&redirect_uri='.urlencode($api->clientWebsite.'/auth?&host='.$domain);
header("Location: {$authorizeURL}", true, 303);
die();
} catch (Exception $e) {
header('Location: '.$api->clientWebsite.'/login?cause=domain', true, 303);
die();
}
}
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Halcyon @ Urusai! Social</title>
<link rel="shortcut icon" href="/assets/images/favicon.ico">
<link rel="stylesheet" href="/login/assets/css/style.css" media="all">
<link rel="stylesheet" href="/assets/css/fontawesome.min.css" media="all">
<link rel="stylesheet" href="/assets/css/cookieconsent.min.css">
<script src="/assets/js/jquery/jquery.min.js"></script>
<script src="/assets/js/cookieconsent/cookieconsent.min.js"></script>
<script src="/assets/js/jquery-cookie/src/jquery.cookie.js"></script>
<script src="/login/assets/js/halcyon_login.js"></script>
<script>
if(
localStorage.getItem("current_id") |
localStorage.getItem("current_instance") |
localStorage.getItem("current_authtoken")
){
location.href = "/";
};
</script>
</head>
<body>
<header id="header">
<div id="header_wrap">
<div id="header_title_wrap" class="header_box header_right_box">
<div class="header_box_child title_box">
<a href="/">
<img src="/login/assets/images/halcyon-title.png" alt="Halcyon for mastodon">
</a>
</div>
</div>
<div id="header_menu_wrap" class="header_box header_left_box">
<nav class="header_box_child nav_box">
<ul>
<a href="https://github.com/neatchee/halcyon" class="no-underline">
<li>
<span><i class="fa fa-code" aria-hidden="true"></i><?=_('Source')?></span>
</li>
</a>
<a href="https://rules.urusai.social/mastodon-coc.html" class="no-underline">
<li>
<span><i class="fa fa-balance-scale" aria-hidden="true"></i><?=_('Terms')?></span>
</li>
</a>
<a href="https://urusai.social/privacy-policy" class="no-underline">
<li>
<span><i class="fa fa-shield" aria-hidden="true"></i><?=_('Privacy')?></span>
</li>
</a>
<?php if(file_exists("../config/imprint.txt")) { ?>
<a href="/imprint" class="no-underline">
<li>
<span><i class="fa fa-id-card-o" aria-hidden="true"></i><?=_('Imprint')?></span>
</li>
</a>
<?php } ?>
<a href="#login_form_wrap" class="no-underline">
<li>
<span><i class="fa fa-user-circle-o" aria-hidden="true"></i><?=_('Login')?></span>
</li>
</a>
</ul>
</nav>
</div>
</div>
</header>
<main id="main">
<div id="login_form_wrap">
<div class="login_form">
<form method="POST" >
<h2><?=_('Log in to Urusai! Social')?></h2>
<p>
<?=_('or')?> <a href="https://urusai.social/auth/sign_up"><?=_('create an account')?></a>
</p>
<div class="session_alert">
<span></span>
</div>
<div class="login_form_main">
<input name="acct" type="text" class="login_form_input" placeholder="@neatchee@urusai.social" required>
<label class="login_form_continue pointer">
<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
<input id="login_continue" type="submit" value="" class="invisible">
</label>
</div>
<div class="login_form_agree">
<input id="agree" type="checkbox" required checked>
<label for="agree" class="login_form_agree_check disallow_select pointer">
<?=_('I agree with the')?> <a href="https://rules.urusai.social/mastodon-coc.html"><?=_('Terms')?></a>
</label>
</div>
</form>
</div>
</div>
<article id="article">
<h1><?= ('This is the alternative UI. Don\'t know what that means? You should probably ')?><a href="https://urusai.social">start here</a></h1>
<h2><?=_('What is Urusai! Social?')?></h2>
<p><?=_('We are a community built around our shared love of anime, manga, gaming, and other otaku culture. Urusai is a place where everyone can feel comfortable engaging with others, whether that\'s about their favorite show, book, game, or anything else that excites them, even if it\'s not typical otaku fare!')?></p>
<p><?= ('This server runs a modified version of the')?><a href="https://github.com/glitch-soc/mastodon"> glitch-soc </a><?=_('version of ')?><a href="https://joinmastodon.org"> Mastodon </a></p>
<p><?=_('We have a strict')?><a href="https://rules.urusai.social/mastodon-coc.html"> Code of Conduct </a><?= ('that details our strong stance against hate and predatory behavior. In short: zero-tolerance for hatred, bigotry, harassment, science or history denialism, lolicon/paedophilia, exploitation, etc.')?></p>
<p><?= ('To learn more about our rules, see the')?><a href="https://urusai.social/about"> About </a><?= ('section of the main instance.')?>
<?= ('To sign up, make sure you read our Code of Conduct and agree to the rules on the About page, then head to the ')?><a href="https://urusai.social/auth/sign_up"> sign-up page</a>.</p>
<h2><?= ('What is Halcyon?')?></h2>
<p><?= ('As users have fled "the birdsite" in droves, some have arrived on Mastodon and been confused or frustrated by the differences from what they\'re used to. Halcyon aims to help alleviate that problem by packaging the Mastodon experience into an interface that is familiar for users from the other place. We provide Halcyon as an option for new users to transition slowly into this new federated world of social networking and hope that you\'ll eventually feel brave enough to join us on the')?><a href="https://urusai.social"> main site.</a></p>
<h2><?=_('Contact')?></h2>
<p>Local: <a href="https://urusai.social/@neatchee" target="_blank"><?= '@neatchee@urusai.social'?></a><br/>
Email: <a href="mailto:neatchee@ansemreport.com" target="_blank"><?=_('neatchee@ansemreport.com')?></a><br/>
Halcyon git repository: <a href="https://github.com/neatchee/halcyon" target="_blank"><?= 'neatchee/halcyon'?></a><br/>
Mastodon git repository: <a href="https://github.com/neatchee/mastodon" target="_blank"><?= 'neatchee/mastodon'?></a></p>
</article>
</main>
<!-- FOOTER -->
<footer id="footer">
<div class="footer_anchor">
<a href="#">
<i class="fa fa-angle-up" aria-hidden="true"></i>
</a>
</div>
<?php
if(file_exists("../config/footerlinks.txt")) {
$footerlinks = json_decode(file_get_contents("../config/footerlinks.txt"));
$haslinks = false;
for($i=0;$i<count($footerlinks);$i++) {
if($footerlinks[$i]->logout == true) {
if($haslinks == false) {
$haslinks = true;
echo "<span>";
}
else {
echo " | ";
}
echo "<a href='".$footerlinks[$i]->link."'>".$footerlinks[$i]->title."</a>";
}
}
if($haslinks == true) {
echo "</span><br/>";
}
}
?>
<span>Halcyon version <?php echo file_get_contents("../version.txt") ?></span>
</footer>
</body>
<script>
window.cookieconsent.initialise({
"palette": {
"popup": {
"background": "#000"
},
"button": {
"background": "#f1d600"
}
},
"theme": "classic",
"position": "bottom"
});
</script>
<?php if (isset($_GET['cause'])): ?>
<script>
$(function() {
var cause = "<?= htmlspecialchars((string)filter_input(INPUT_GET, 'cause'), ENT_QUOTES) ?>";
if(cause === "domain") {
$('.login_form_main').addClass('error');
$('.session_aleart').removeClass('invisible');
$('.session_aleart > span').text('This instance does not exist.');
}
});
$(document).on('click','.login_form_main', function(e) {
$(this).removeClass('error');
});
</script>
<?php endif; ?>
</html>
