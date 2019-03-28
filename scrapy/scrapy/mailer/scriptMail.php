<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';

try {
    $base = new PDO('mysql:host=localhost:3306;dbname=marketmonitor','root','');
}
catch(exception $e) {
    die('Erreur '.$e->getMessage());
}


$userquery = $base->query('SELECT * FROM user');
$message ='<html><head></head><body>';
while ($user = $userquery->fetch()) {
    $ean = array();
    $mail = false;
    $seuil = false;
    $message .= $user['user_mail'].' '.$user['user_pseudo'];
    $listquery = $base->query("SELECT * FROM list_products
                           WHERE list_products_mail ='".$user['user_mail']."'
                           and list_products_suivi =1");

    while ($listproduct = $listquery->fetch()) {
        $pricequery = $base->query("SELECT * FROM price
                               WHERE price_ean ='".$listproduct['list_products_ean']."'");

        while ($listprice = $pricequery->fetch()) {
            if ($listprice['price_price_exponent'] > $listproduct['list_products_seuil']){
                $seuil = true;
                $mail = true;

            }
        }
        if ($seuil){
            array_push($ean,$listproduct['list_products_ean'] );
        }
        $seuil = false;
    }
    if ($mail){
        $mail = new PHPmailer();
        $mail->SMTPDebug = 1;
        $mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Host = 'smtp.gmail.com'; // Spécifier le serveur SMTP
        $mail->SMTPAuth = true; // Activer authentication SMTP
        $mail->Username = 'marketmonitor35@gmail.com'; // Votre adresse email d'envoi
        $mail->Password = '2140market'; // Le mot de passe de cette adresse email
        $mail->SMTPSecure = 'ssl'; // Accepter SSL
        $mail->Port = 465;

        $mail->setFrom('marketmonitor35@gmail.com', 'Market Monitor'); // Personnaliser l'envoyeur
        $mail->addAddress($user['user_mail'], $user['user_pseudo']); // Ajouter le destinataire

        $mail->isHTML(true); // Paramétrer le format des emails en HTML ou non

        $mail->Subject = 'Alerte produits suivis';
        $mail->Body = '<p>Bonjour '.$user['user_pseudo'].',</p>';
        $mail->Body .='<p>Le ou les produit(s) suivant(s) sont en dessous du prix que vous aviez renseigné :</p>';
        $mail->Body .='<ul>';
        foreach ($ean as $produit) {
            $nomquery = $base->query("SELECT product_name from product
                                               WHERE product_ean ='" . $produit . "'");
            while ($listnom = $nomquery->fetch()) {

                $mail->Body .= '<li>' . $listnom['product_name'] . '</li>';

            }
        }
        $mail->Body .='</ul>';
        $mail->Body .= '<p>Vous pouvez retrouvez vos produits suivis à ce lien en vous connectant: 
                        <a href="http://localhost/site_web/produitSuivi">Vos produits suivis</a></p>
        <br>
        <br>
        <p>Cordialement,</p>
        <p>Votre market monitor</p>';

        if(!$mail->send()) {
            echo 'Erreur, message non envoyé.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Le message a bien été envoyé !';
        }
    }
}
$base = null;

