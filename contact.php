
<?php
        
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
    
        require 'PHPMailer/src/Exception.php'; 
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
    
    
    //Create an instance; passing `true` enables exceptions
    $MailGonder = new PHPMailer(true);
    ?>

    <?php
//SETTİNGS


try {
    $Veritabanibaglantisi   =   new PDO("mysql:host=localhost;dbname=id14546080_emin;charset=UTF8", "id14546080_emindb", "Deneme12345@");
} catch (PDOException $Hata) {
    echo "Bağlantı Hatası<br />"; // $Hata->getMessage(); Bu alanı kapatın çükü kullunıcılar hata kodunu görmesin ok
    die();
}
$AyarlarSorgusu     =   $Veritabanibaglantisi->prepare("SELECT * FROM ayarlar LIMIT 1");
$AyarlarSorgusu->execute();
$AyarlarSayisi      =   $AyarlarSorgusu->rowCount();
$Ayarlar            =   $AyarlarSorgusu->fetch(PDO::FETCH_ASSOC);
if ($AyarlarSayisi>0) {
    $SiteAdi                =  $Ayarlar["SiteAdi"];
    $SiteTitle              =  $Ayarlar["SiteTitle"];
    $SiteDescription        =  $Ayarlar["SiteDescription"];
    $SiteKeywords           =  $Ayarlar["SiteKeywords"];
    $SiteCopyrightMetini    =  $Ayarlar["SiteCopyrightMetini"];
    $SiteLogosu             =  $Ayarlar["SiteLogosu"];
    $SiteEmailAdresi        =  $Ayarlar["SiteEmailAdresi"];
    $SiteEmailSifresi       =  $Ayarlar["SiteEmailSifresi"];
    $SiteEmailHostAdresi    =  $Ayarlar["SiteEmailHostAdresi"];

    $FacebookLink          =  $Ayarlar["Facebook_Link"];
    $TwitterLink           =  $Ayarlar["Twitter_Link"];
    $InstagramLink         =  $Ayarlar["Instagram_Link"];
    $LinkedInLink          =  $Ayarlar["LinkedIn_Link"];
    $PinterestLink         =  $Ayarlar["Pinterest_Link"];
    $YoutubeLink           =  $Ayarlar["Youtube_Link"];

}else{
    //echo "Site ayar sorgusu hatalı"; // bu alanı kaptta kullanıcılar görmesin
    die();
}

$MetinlerSorgusu     =   $Veritabanibaglantisi->prepare("SELECT * FROM sozlesmelervemetinler LIMIT 1");
$MetinlerSorgusu->execute();
$MetinlerSayisi      =   $MetinlerSorgusu->rowCount();
$Metinler            =   $MetinlerSorgusu->fetch(PDO::FETCH_ASSOC);
if ($MetinlerSayisi>0) {
    $Hakkimizda                 =  $Metinler["Hakkimizda"];
    $UyelikSozlesmesiMetni      =  $Metinler["UyelikSozlesmesiMetni"];
    $KullanimKosullari          =  $Metinler["KullanimKosullariMetni"];
    $GizlilikSozlesmesi         =  $Metinler["GizlilikSozlesmesi"];
    $MesafeliSatisSozlesmesi    =  $Metinler["MesafeliSatisSozlesmesi"];
    $TeslimatMetni              =  $Metinler["TeslimatMetni"];
    $IptaliadeDegisim           =  $Metinler["IptaliadeDegisimMetni"];
}else{
    //echo "Site metin sorgusu hatalı"; // bu alanı kaptta kullanıcılar görmesin
    die();
}
if(isset($_SESSION["Kullanici"]["0"] )){
$UyelerSorgusu     =   $Veritabanibaglantisi->prepare("SELECT * FROM uyeler WHERE EmailAdresi = " . $_SESSION["Kullanici"]["0"] . " LIMIT 1");
$UyelerSorgusu->execute();
$UyelerSayisi      =   $UyelerSorgusu->rowCount();
$Uyeler            =   $UyelerSorgusu->fetch(PDO::FETCH_ASSOC);
if ($UyelerSayisi>0) {
    $UyeID                 =  $Uyeler["id"];
    $UyeEmailAdresi        =  $Uyeler["EmailAdresi"];
    $UyeSifre              =  $Uyeler["Sifre"];
    $UyeIsimSoyisim        =  $Uyeler["IsimSoyisim"];
    $UyeTelefonNumarasi    =  $Uyeler["TelefonNumarasi"];
    $UyeCinsiyet           =  $Uyeler["Cinsiyet"];
    $UyeDurumu             =  $Uyeler["Durumu"];
    $UyeKayitTarihi        =  $Uyeler["KayitTarihi"];
    $UyeKayitIPAdresi      =  $Uyeler["KayitIPAdresi"];
    $HesapAktivasyonKodu   =  $Uyeler["AktivasyonKodu"];
}else{
    //echo "Üye bilgileri sorgusu hatalı"; // bu alanı kaptta kullanıcılar görmesin
    die();
}
}



    //FUNCTIONS
$IPAdresi       =   $_SERVER["REMOTE_ADDR"];
$ZamanDamgasi   =   time();
$TarihSaat      =   date("d.m.Y H:i:s", $ZamanDamgasi);


function RakamlarHaricTumKarakterleriSil($Deger){
    $Islem          =   preg_replace("/[^0-9]/", "", $Deger);
    $Sonuc              =   $Islem;
    return $Sonuc;
}

function TumBosluklariSil($Deger){
    $Islem          =   preg_replace("/\s|&nbsp;/", "", $Deger);
    $Sonuc              =   $Islem;
    return $Sonuc;
}

function SayiliIcerikleriFiltrele($Deger){
    $BoslukSil          =   trim($Deger);
    $TaglariTemizle     =   strip_tags($BoslukSil);
    $EtkisizYap         =   htmlspecialchars($TaglariTemizle);
    $Temizle            =   RakamlarHaricTumKarakterleriSil($EtkisizYap);
    $Sonuc              =   $Temizle;
    return $Sonuc;
}

function Guvenlik($Deger){
    $BoslukSil          =   trim($Deger);
    $TaglariTemizle     =   strip_tags($BoslukSil);
    $EtkisizYap         =   htmlspecialchars($TaglariTemizle, ENT_QUOTES);
    $Sonuc              =   $EtkisizYap;
    return $Sonuc;
}

function DonusumleriGeriDondur ($Deger){
    $GeriDondur  =   htmlspecialchars_decode($Deger, ENT_QUOTES);
    $Sonuc                  =   $GeriDondur;
    return $Sonuc;
}

function IBANBicimlendir($Deger){
    $BoslukSil          =   TumBosluklariSil($Deger);
    $Birinciblok        =   substr($BoslukSil, 0, 4);
    $IkinciBlok         =   substr($BoslukSil, 4, 4);
    $UcuncuBlok         =   substr($BoslukSil, 8, 4);
    $DorduncuBlok       =   substr($BoslukSil, 12, 4);
    $BesinciBlok        =   substr($BoslukSil, 16, 4);
    $AltinciBlok        =   substr($BoslukSil, 20, 4);
    $YedinciBlok        =   substr($BoslukSil, 24, 2);
    $Duzenle            =   $Birinciblok . " " . $IkinciBlok . " " . $UcuncuBlok . " " . $DorduncuBlok . " " . $BesinciBlok . " " . $AltinciBlok . " " . $YedinciBlok;
    $Sonuc              =   $Duzenle;    
    return $Sonuc;
}


function ActivationCode(){
    $IlkUclu            =   rand(10000, 99999);   
    $IkinciUclu         =   rand(10000, 99999);   
    $UcuncuUclu         =   rand(10000, 99999);   
    $Kod                =   "AC" . $IlkUclu . "-" . $IkinciUclu . "-" . $UcuncuUclu ;
    $Sonuc              =   $Kod;
    return $Sonuc;
}

function MailGonder($HostAdresi, $MailAdresi, $MailSifresi, $GonderenMailAdresi, $GonderenAdi, $AliciAdi, $AliciMailAdresi, $YanitAdi, $YanitAdresi, $Konu, $Mesaj){
    
    // require "../allwebone/Ayarlar/ayar.php"; 
    // require './Frameworks/PHPMailer/src/Exception.php'; 
    // require './Frameworks/PHPMailer/src/PHPMailer.php';
    // require './Frameworks/PHPMailer/src/SMTP.php';

    $MailGonder = new PHPMailer(true);
                    //phpmailer aktivasyon maili gönderimi
try {
    //Server settings
    $MailGonder->SMTPDebug          =  0;                      //Enable verbose debug output
    $MailGonder->isSMTP();                                            //Send using SMTP
    $MailGonder->Host               =   $HostAdresi;                     //Set the SMTP server to send through
    $MailGonder->SMTPAuth           =   true;                                   //Enable SMTP authentication
    $MailGonder->CharSet            =   "UTF-8";
    $MailGonder->Username           =   $MailAdresi;                     //SMTP username
    $MailGonder->Password           =   $MailSifresi;                               //SMTP password
    $MailGonder->SMTPSecure         =   "ssl";            //Enable implicit TLS encryption
    $MailGonder->Port               =   465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $MailGonder->SMTPOptions        =   array(
                                                    'ssl' => array(
                                                    'verify_peer' => false,
                                                    'verify_peer_name' => false,
                                                    'allow_self_signed' => true
                                                )
                                            );

    //Recipients
    $MailGonder->setFrom($GonderenMailAdresi, $GonderenAdi);
    $MailGonder->addAddress($AliciAdi, $AliciMailAdresi);     //Add a recipient
                             //Name is optional
    $MailGonder->addReplyTo($YanitAdi, $YanitAdresi);

    //Content
    $MailGonder->isHTML(true);                                  //Set email format to HTML
    $MailGonder->Subject = $Konu;
    $MailGonder->MsgHTML($Mesaj);


//                 ob_start();
// include "file.html";
// $contents = ob_get_clean();
// $mail->msgHTML($contents);
                                            
    $MailGonder->send();
   // echo 'Message has been sent';
} catch (Exception $e) {
    //phpmailerın error kısmı
    // echo "Message could not be sent. Mailer Error: {$MailGonder->ErrorInfo}";
    ?> <script> alert("PHPMailer hatası. Lütfen bizim ile iletişime geçin.");</script> <?php
     } 

}

    ?>





<!DOCTYPE html>
<html lang="tr">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preload" href="LeagueSpartan-Bold.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script type="text/javascript" src="Frameworks/jquery/jquery-3.6.0.min.js" language="javascript"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jura:wght@300;500&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" media="(max-width:768px)" href="tablet.css">
    <link rel="stylesheet" media="(max-width:500px)" href="mobile.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futurespt</title>
	<link rel="shortcut icon" href="1/images/favicon.ico" type="image/x-icon" />
	<meta name="description" content="Future Spotting Team Official Website">
    <meta name="keywords" content="fotoğraf,fotoğrafçı,spotter,havacı,aviator,aviation,futurespt,future spotting,future spotting team,spotting team,">
    <meta name="author" content="Eminztp"> 
</head>

<body>


    <!-- NAVİGATİON -->
    <div class="navigation">
        <input type="checkbox" name="navi" id="navi-check" class="navi-check">
        <label for="navi-check" class="navi-label">
                <span class="navi-icon"></span>
            </label>
        <div class="navi-bg"></div>
        <nav class="navi-menu">
        <ul class="navi-list">
                <li class="navi-list-item">
                    <a href="../index.html" class="navi-link">HOME</a>
                </li>
                <li class="navi-list-item">
                    <a href="about.html" class="navi-link">ABOUT</a>
                </li>
                <li class="navi-list-item">
                    <a href="team.html" class="navi-link">TEAM</a>
                </li>
                <li class="navi-list-item">
                    <a href="photos.html" class="navi-link">PHOTOS</a>
                </li>
                <li class="navi-list-item">
                    <a href="contact.php" class="navi-link">CONTACT</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- LOGO -->
    <div class="logo">
        <a href="../index.html">
            <h1 class="footer-title ">Future </br> Spotting Team</h1>
        </a>
    </div>


    <div class="header-photo-global">
        <img src="images/bircanbicersahne.jpeg" alt="sahne" id="header-photo-global">
        <div class="black-filter sahne"></div>
        <h1>contact</h1>
    </div>
</body>


<main class="contact">
    <div class="links-cont">
        <div class="mailto-cont">

            <a href="1/mailto:contact@futurespt.com">contact@futurespt.com</a>
            <a href="1/mailto:turhan@futurespt.com">turhan@futurespt.com</a>
            <a href="1/mailto:yagiz@futurespt.com">yagiz@futurespt.com</a>
            <a href="1/mailto:emin@futurespt.com">emin@futurespt.com</a>
            <a href="1/mailto:mustafa@futurespt.com">mustafa@futurespt.com</a>
        </div>
        <div class="insta-cont">
            <a href="1/https://www.instagram.com/futurespt/" target="_blank">
                <p><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" class="svg-inline--fa fa-instagram fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></p>

                <!-- <h3 class="ig-username">futurespt</h3> -->
            </a>
            <a href="1/https://www.instagram.com/futurespt/" target="_blank">
                <p><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" class="svg-inline--fa fa-instagram fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></p>

                <!-- <h3 class="ig-username">turhan</h3> -->
            </a>
            <a href="1/https://www.instagram.com/futurespt/" target="_blank">
                <p><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" class="svg-inline--fa fa-instagram fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></p>

                <!-- <h3 class="ig-username">yağız</h3> -->
            </a>
            <a href="1/https://www.instagram.com/futurespt/" target="_blank" class="emin-insta">
                <p><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" class="svg-inline--fa fa-instagram fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></p>

                <!-- <h3 class="ig-username">emin</h3> -->
            </a>
            <a href="1/https://www.instagram.com/futurespt/" target="_blank" class="mustafa-insta">
                <p><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" class="svg-inline--fa fa-instagram fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></p>

                <!-- <h3 class="ig-username">mustafa</h3> -->
            </a>
        </div>
    </div>

    <div class="phpmail">

    
    
    <?php
    //ilfo  =   İletişim Formu
    if(!$_POST){
    //POST YOK İSE BU ALAN ÇALIŞACAK
    ?>
    

    <form action="contact.php" method="post" class="formcont">
        <div id="">
            <!-- <div class="alert-cont bhf-eksiksiz-alert-cont">
                <p class="bhfalerttext alert">  </p>
            </div> -->
            <div class="formitem">
                <input class="forminput" type="text" name="isimsoyisim" placeholder="İsim Soyisminiz"
                    required>
            </div>


            <div class="formitem">
                <input class="forminput" type="text" name="eposta" placeholder="E-Posta Adresiniz" required>
            </div>




            <div class="formitem">
                <textarea id="formtextarea" class="forminput" name="mesaj"
                    placeholder="Mesajınız..." required></textarea>
            </div>

            <div class="formitem" id="submit-btn-cont">
                <input id="submit-btn" class="formsubmit" type="submit" value="Gönder">
            </div>

        </div>
    </form>

    <?php //İF(!$_POST) KISMININ if BÖLÜMÜNÜN BİTİŞİ  
}else{  //İF(!$_POST) KISMININ else BÖLÜMÜNÜN BAŞLANGICI (POST VARSA BURASI ÇALIŞACAK)
    $il_fo_isimsoyisim     =   Guvenlik($_POST['isimsoyisim']);
    $il_fo_eposta          =   Guvenlik($_POST['eposta']);
    $il_fo_mesaj           =   Guvenlik($_POST['mesaj']);
    
    // echo $il_fo_isimsoyisim;
    // echo $il_fo_eposta;
    // echo $il_fo_telefon;
    // echo $il_fo_mesaj;
    

    //İsim Soyisim RegEx
    $patternisimsoyisim = "/^[a-zA-Z çÇıİşŞğĞüÜöÖ.'-]+$/"; // 
    preg_match($patternisimsoyisim, $il_fo_isimsoyisim, $isimsoyisim);
    
    //E-Posta RegEx
    $patterneposta = '/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/';
    preg_match($patterneposta, $il_fo_eposta, $eposta);

?>
 

 <?php


if (empty($isimsoyisim) or empty($eposta) or empty($il_fo_mesaj)) {
    //POST VAR AMA REGEX'E UYMUYOR VEYA BOŞ ALAN VARSA BURASI ÇALIŞACAK.
   ?>

<form action="contact.php" method="post" class="formcont">
        
        <div class="alert-cont ">
                        <p class="bhfalerttext alert"> Lütfen tüm alanları eksiksiz ve uygun olarak doldurunuz. </p>
                    </div>
            <div class="formitem">
                <input class="forminput" type="text" name="isimsoyisim" placeholder="İsim Soyisminiz"
                    required>
            </div>


            <div class="formitem">
                <input class="forminput" type="text" name="eposta" placeholder="E-Posta Adresiniz" required>
            </div>




            <div class="formitem">
                <textarea id="formtextarea" class="forminput" name="mesaj"
                    placeholder="Mesajınız..." required></textarea>
            </div>

            <div class="formitem" id="submit-btn-cont">
                <input id="submit-btn" class="formsubmit" type="submit" value="Gönder">
            </div>

        </div>
    </form>

    <?php }else{ //HEM POST VAR HEMDE REGEX'E Mİ UYUYORSA BURASI ÇALIŞACAK  
        $MailIcerigi = "İsim Soyisim: " . $isimsoyisim[0] . "<br />" .
        "E-Posta Adresi: " . $eposta[0] . "<br />" .
        "Mesaj: " . $il_fo_mesaj;


    
        try {
            //Server settings
            $MailGonder->SMTPDebug          =   0;                      //Enable verbose debug output
            $MailGonder->isSMTP();                                            //Send using SMTP
            $MailGonder->Host               =   DonusumleriGeriDondur($SiteEmailHostAdresi);                     //Set the SMTP server to send through
            $MailGonder->SMTPAuth           =   true;                                   //Enable SMTP authentication
            $MailGonder->CharSet            =   "UTF-8";
            $MailGonder->Username           =   DonusumleriGeriDondur($SiteEmailAdresi);                     //SMTP username
            $MailGonder->Password           =   DonusumleriGeriDondur($SiteEmailSifresi);                               //SMTP password
            $MailGonder->SMTPSecure         =   "ssl";            //Enable implicit TLS encryption
            $MailGonder->Port               =   465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $MailGonder->SMTPOptions        =   array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        
            //Recipients
            $MailGonder->setFrom(DonusumleriGeriDondur($SiteEmailAdresi), DonusumleriGeriDondur($SiteAdi));
            $MailGonder->addAddress(DonusumleriGeriDondur($SiteEmailAdresi), DonusumleriGeriDondur($SiteAdi));     //Add a recipient
                                     //Name is optional
            $MailGonder->addReplyTo(DonusumleriGeriDondur($il_fo_eposta), DonusumleriGeriDondur($il_fo_isimsoyisim));
        
            //Content
            $MailGonder->isHTML(true);                                  //Set email format to HTML
            $MailGonder->Subject = DonusumleriGeriDondur($SiteAdi) . ' İletişim Formu Mesajı';
            $MailGonder->MsgHTML($MailIcerigi);
        
            $MailGonder->send();
           // echo 'Message has been sent';
        } catch (Exception $e) {
            //phpmailerın error kısmı
            //echo "Message could not be sent. Mailer Error: {$MailGonder->ErrorInfo}";?>
        

        <form action="contact.php" method="post" class="formcont">
        
        <div class="alert-cont ">
                        <p class="bhfalerttext alert"> Lütfen tüm alanları eksiksiz ve uygun olarak doldurunuz. </p>
                    </div>
            <div class="formitem">
                <input class="forminput" type="text" name="isimsoyisim" placeholder="İsim Soyisminiz"
                    required>
            </div>


            <div class="formitem">
                <input class="forminput" type="text" name="eposta" placeholder="E-Posta Adresiniz" required>
            </div>




            <div class="formitem">
                <textarea id="formtextarea" class="forminput" name="mesaj"
                    placeholder="Mesajınız..." required></textarea>
            </div>

            <div class="formitem" id="submit-btn-cont">
                <input id="submit-btn" class="formsubmit" type="submit" value="Gönder">
            </div>

        </div>
    </form>
    <script> alert("PHPMailer hatası. Lütfen bizim ile iletişime geçin.");</script>
   

    <?php  die();
    } ?>




    <form action="contact.php" method="post" class="formcont">
        <div id="">
        <div class="alert-cont ">
                <p class="bhfalerttext alert-succsess alert"> Mesajınızı aldık en kısa sürede geri dönüş yapacağız. </p>
                    </div>
            <div class="formitem">
                <input class="forminput" type="text" name="isimsoyisim" placeholder="İsim Soyisminiz"
                    required>
            </div>


            <div class="formitem">
                <input class="forminput" type="text" name="eposta" placeholder="E-Posta Adresiniz" required>
            </div>




            <div class="formitem">
                <textarea id="formtextarea" class="forminput" name="mesaj"
                    placeholder="Mesajınız..." required></textarea>
            </div>

            <div class="formitem" id="submit-btn-cont">
                <input id="submit-btn" class="formsubmit" type="submit" value="Gönder">
            </div>

        </div>
    </form>





    <?php } ?>
<?php  }//İF(!$_POST) KISMININ else BÖLÜMÜNÜN BİTİŞİ ?>














    </div>
</main>


<!-- YUKARI ÇIK BUTONU -->
<a href="1/#top" id="yukari-cik" title="Yukarı Çık"></a>
<script>
jQuery(document).ready(function($){
    $(window).scroll(function(){
        if ($(this).scrollTop() < 850) {
            $('#yukari-cik') .fadeOut();
        } else {
            $('#yukari-cik') .fadeIn();
        }
    });
    $('#yukari-cik').on('click', function(){
        $('html, body').animate({scrollTop:0}, 'fast');
        return false;
        });
});
</script>
        </body>

        
<footer>
    <div class="footer-li-cont">

    <li><a href="../index.html">home</a></li>
        <li><a href="about.html">about</a></li>
        <li><a href="photos.html">photos</a></li>
        <li><a href="team.html">team</a></li>
        <li><a href="contact.php">contact</a></li>
    </div>

    <div class="footer-logo-cont">

        <h2 class="footer-title">Future </br> Spotting Team</h2>
        <h6>copyright © 2022 all rights reserved</h6>

    </div>
</footer>
</html>