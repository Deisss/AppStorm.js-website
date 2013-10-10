<?php
session_start();
require(dirname(__FILE__).DIRECTORY_SEPARATOR."database.php");

/*
-------------------------------
  VAR
-------------------------------
*/

// 1800 sec : 30 min before deleting db
$old = 1800;
// Naming it with "." before help to add filter to it
$dbname = ".appstorm";

// Refer to database class to keep them linked in case of modification
$dbfile = dirname(__FILE__).DS.DB_FOLDER.DS.$dbname.".sqlite3";

















/*
-------------------------------
  FUNCTION
-------------------------------
*/

/**
 * This function check an email is valid or not, it also check if the email is linked to a droppable email system or not
 *
 * @param $emailToCheck string The email to check.
 * @return mixed False if there is a problem (email is not valid, or email is in droppable provider list), the email if everything succeed
*/
function check($emailToCheck){
	$parsed = filter_var(strtolower(trim($emailToCheck)), FILTER_VALIDATE_EMAIL);

	//Prevent default problem
	if($parsed === false) {
		return false;
	}

	$deny = array("yopmail.com","yopmail.fr","brefmail.com","uggsrock.com","haltospam.com","kleemail.com","email-jetable.eu","email-jetable.com","destroy-spam.com","justonemail.net","letmymail.com","onemoremail.net","yopmail.fr","yopmail.net","cool.fr.nf","nospam.ze.tc","nomail.xl.cx","mega.zik.dj","speed.1s.fr","courriel.fr.nf","moncourrier.fr.nf","monemail.fr.nf","monmail.fr.nf","filzmail.com","kleemail.com","email-jetable.eu","destroy-spam.com","trash-mail.com","mail-temporaire.fr","tempomail.fr","pjjkp.com","mail.ru","keepmymail.com","0-mail.com","jnxjn.com","mailincubator.com","courrieltemporaire.com","anonymbox.com","sharklasers.com","guerrillamailblock.com","guerrillamailblock.net","guerrillamailblock.biz","guerrillamailblock.org","guerrillamailblock.de","guerrillamailblock.fr","guerrillamailblock.en","guerrillamailblock.uk","guerrillamailblock.me","guerrillamail.com","guerrillamail.net","guerrillamail.biz","guerrillamail.org","guerrillamail.de","guerrillamail.fr","guerrillamail.en","guerrillamail.uk","guerrillamail.me","mytempemail.com","saynotospams.com","tempemail.co.za","mailinator.com","mailinator2.com","mytrashmail.com","thankyou2010.com","trash2009.com","mt2009.com","trashymail.com","jetable.org","jetable.net","jetable.fr.nf","spamcorptastic.com","mailexpire.com","maileater.com","rtrtr.com","spamfree24.org","spamfree24.eu","spamfree24.net","spamfree24.info","spamfree24.de","spamfree24.com","ephemail.com","trashmail.net","trashmail.me","trashmail.at","kasmail.com","spamgourmet.com","tempomail.com","web.de","gawab.com","cashette.com","mail.ru","inbox.ru","bk.ru","yandex.ru","nightmail.ru","spambox.us","10minutemail.com","dontreg.com","link2mail.com","link2mail.net","dodgeit.com","e4ward.com","gishpuppy.com","mailnull.com","nobulk.com","nospamfor.us","pookmail.com","shortmail.net","sneakemail.com","spam.la","spam.su","spambob.com","spamday.com","spamh0le.com","spaml.com","tempinbox.com","temporaryinbox.com","willhackforfood.biz","willselfdestruct.com","wuzupmail.net","meltmail.com","get2mail.com","rppkn.com","spamavert.com","iximail.com","mailbidon.com","mailbidon.fr","xblogz.org","sogetthis.com","mailin8r.com","spamherelots.com","thisisnotmyrealemail.com","wh4f.org","pourri.fr","dupemail.com","correo.blogos.net","1-12.nl","127-0-0-1.be","3v1l0.com","aliraheem.com","aliscomputer.info","bankofuganda.dontassrape.us","black-arm.cn","black-leg.cn","black-tattoo.cn","blacktattoo.cn","bonaire.in","casema.org","churchofscientology.org.uk","copcars.us","definatelynotaspamtrap.com","djlol.dk","edwinserver.se","fuzzy.weasel.net","har2009.cn","hermesonline.dk","m.nonumberno.name","jpshop.ru","junk-yard.be","junk-yard.eu","laughingman.ath.cx","linux.co.in","lolinternets.com","madcrazydesigns.com","maleinhandsmint.czarkahan.net","newkurdistan.com","nigerianscam.dontassrape.us","ninjas.dontassrape.us","no-spam.cn","omicron.token.ro","pengodam.biz","pirates.dontassrape.us","pirazine.se","s.blackhat.lt","sales.bot.nu","sales.net-freaks.com","sendmeshit.com","slarvig.se","slaskpost.cn","slaskpost.se","slop.jerkface.net","slops.lazypeople.co.uk","slops.quadrath.com","slopsbox.com","slopsbox.net","slopsbox.org","slopsbox.se","slopsbox.slarvig.se","slopsbox.spammesenseless.dk","slopsbox.stivestoddere.dk","solidblacktattoo.cn","spam.dontassrape.us","spam.h0lger.de","spam.hack.se","spam.mafia-server.net","spam.tagnard.net","spam.w00ttech.com","spamout.jassi.info","thegaybay.com","trash-can.eu","tyros2.cn","vuilnisbelt.cn","watertaxi.net","west.metaverseaudio.com","your.gay.cat","ziggo.ws","zynd.com","realcambio.com","watchnode.uni.cc","gimme.wa.rez.se","pyramidspel.com","slopsbox.osocial.nu","kaspop.com","farifluset.mailexpire.com","tempemail.net","33mail.com","mailcatch.com","incognitomail.org","deadaddress.com","soodonims.com","dispostable.com","ab.mintemail.com","mailboxable.net","spammotel.com","onewaymail.com","no-spam.ws","meltmail.com","mailmoat.com","mailme24.com","spam-be-gone.com","spamgourmet.com","stop-my-spam.com","spamex.com","burnthespam.info","eyepaste.com","fakemailgenerator.com","fornow.eu","freemail.ms","kurzepost.de","objectmail.com","proxymail.eu","rcpt.at","trash-mail.at","wegwerfmail.org","wegwerfmail.net","wegwerfmail.de","24hourmail.com","crapmail.org","shitmail.org","email-anonyme.net","dingbone.com","fudgerub.com","lookugly.com","smellfear.com","crdf.fr","cortexmail.com","spamfr.com","spamenmoins.com","hidemyass.com","boxbe.com","mailtemp.net","mail2rss.org");
	$domain = substr($parsed, strrpos($parsed, '@') + 1);

	//Checking the domain is not in droppable domain list
	if(in_array($domain, $deny)) {
		return false;
	}

	//If everything succeed, we allow it
	return $parsed;
}

/**
 * Retrieve submitted var by user
 *
 * @param $var {String} The var to retrieve
*/
function get($var) {
	if(isset($_POST) && !empty($_POST[$var])) {
		return htmlentities(trim($_POST[$var]), ENT_QUOTES, "UTF-8");
	}
	return null;
}


















/*
-------------------------------
  - SECURITY (SESSION)
-------------------------------
*/

if(!isset($_SESSION)) {
	$_SESSION = array();
}

if(!isset($_SESSION["max_contact"]) || empty($_SESSION["max_contact"])) {
	$_SESSION["max_contact"] = 1;
}

if(!empty($_SESSION["max_contact"])) {
	$max = $_SESSION["max_contact"];
	$max++;
	$_SESSION["max_contact"] = $max;
}

if($_SESSION["max_contact"] > 5) {
	die("Please wait a moment (15 min), you already try to contact us a lot");
}
















/*
-------------------------------
  - SECURITY (IP)
-------------------------------
*/

// Retrieve user IP
$ip = ip();
$db = database::connect($dbname);

// Inserting into database
$ipRenew = $db->prepare("INSERT INTO seen (id, ip) VALUES(NULL, :ip)");
$ipRenew->bindParam(":ip", $ip, PDO::PARAM_STR, 48);
$ipRenew->execute();

// Count how many into database
$ipExist = $db->prepare("SELECT id FROM seen WHERE ip = :ip");
$ipExist->bindParam(":ip", $ip, PDO::PARAM_STR, 48);
$ipExist->execute();

// Get result
$resultIpExist = $ipExist->fetchAll(PDO::FETCH_ASSOC);

//No email exists, adding a client
if(count($resultIpExist) > 20) {
	die("Too many access with this account");
}

// Free resources
$db = null;

// Flush database if too old
if((time()-filectime($dbfile)) < $old) {
	@unlink($dbfile);
}
















/*
-------------------------------
  - MAIL
-------------------------------
*/

$name    = get("name");
$email   = check(get("email"));
$subject = get("subject");
$message = get("message");

if($email === false) {
	die("Can't proceed a wrong email, or temporary email address");
}

if($message === null || $email === null) {
	die("Can't send a message with an empty email, and/or an empty message");
}

if($subject === null) {
	$subject = "";
}
if($name === null) {
	$name = "";
}

$lmessage = strlen($message);
if($lmessage <= 1 || $lmessage >= 4096) {
	die("The message must be between 1 and 4096 characters");
}

$lemail   = strlen($email);
if($lemail <= 5 || $lemail >= 96) {
	die("The email must be between 5 and 96 characters");
}

// Now we can send email
$headers ='From: <'.$email.'>'."\n";
$headers .='Reply-To: '.$email."\n";
$headers .='Content-Type: text/plain; charset="UTF-8"'."\n";
$headers .='Content-Transfer-Encoding: 8bit';

// Prepare message to send
$content = <<<CONTENT
	name    : $name
	email   : $email
	subject : $subject

	message :
	$message
CONTENT;

// Trying to send email
if(mail("charles.villette@appstormjs.com", "[AppStorm.JS] contact", $content, $headers)) {
	die("ok");
} else {
	die("error");
}
?>