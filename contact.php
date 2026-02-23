<?php
/**
 * contact.php — Formulaire de contact Decizif
 * Utilise PHPMailer pour envoyer les messages via SMTP Hostinger
 *
 * INSTALLATION :
 * 1. Uploader ce fichier à la racine du site (même dossier que decizif.html)
 * 2. Installer PHPMailer : uploader le dossier /PHPMailer/ (voir README.txt)
 * 3. Remplacer TON_MOT_DE_PASSE par le mot de passe de jfgalles@decizif.fr
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$nom     = trim(strip_tags($_POST['nom']     ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$sujet   = trim(strip_tags($_POST['sujet']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

if (empty($nom) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Merci de remplir tous les champs obligatoires.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jfgalles@decizif.fr';
    $mail->Password   = 'NlUVU,)|>$=\IJc!WNwZkm==[/4g*tt"0@;bnD5~';   // ← REMPLACER ICI
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('jfgalles@decizif.fr', 'Formulaire site web decizif');
    $mail->addAddress('jfgalles@decizif.fr', 'Jean-François Galles');
    $mail->addReplyTo($email, $nom);

    $sujetMail = $sujet ? "[$sujet] Message de $nom" : "Message de $nom via decizif.fr";
    $mail->Subject = $sujetMail;

    $altBody = "Nouveau message depuis decizif.fr\n\nNom : $nom\nEmail : $email\n" . ($sujet ? "Sujet : $sujet\n" : "") . "Message :\n$message\n\n---\nEnvoyé depuis decizif.fr";

    $htmlBody = "
    <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
        <h2 style='color:#0D1B2A;border-bottom:2px solid #00C2FF;padding-bottom:10px;'>Nouveau message — decizif.fr</h2>
        <table style='width:100%;border-collapse:collapse;'>
            <tr><td style='padding:8px;font-weight:bold;color:#555;width:80px;'>Nom</td><td style='padding:8px;'>".htmlspecialchars($nom)."</td></tr>
            <tr style='background:#f9f9f9;'><td style='padding:8px;font-weight:bold;color:#555;'>Email</td><td style='padding:8px;'><a href='mailto:".htmlspecialchars($email)."'>".htmlspecialchars($email)."</a></td></tr>
            ".($sujet ? "<tr><td style='padding:8px;font-weight:bold;color:#555;'>Sujet</td><td style='padding:8px;'>".htmlspecialchars($sujet)."</td></tr>" : "")."
            <tr style='background:#f9f9f9;'><td style='padding:8px;font-weight:bold;color:#555;vertical-align:top;'>Message</td><td style='padding:8px;'>".nl2br(htmlspecialchars($message))."</td></tr>
        </table>
        <p style='color:#999;font-size:12px;margin-top:20px;border-top:1px solid #eee;padding-top:10px;'>Envoyé depuis decizif.fr</p>
    </div>";

    $mail->isHTML(true);
    $mail->Body    = $htmlBody;
    $mail->AltBody = $altBody;

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Votre message a bien été envoyé. Je vous répondrai rapidement !']);

} catch (Exception $e) {
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "L'envoi a échoué. Contactez-moi directement à jfgalles@decizif.fr"]);
}
