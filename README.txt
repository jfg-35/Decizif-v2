==============================================
DECIZIF.FR — Installation formulaire de contact
==============================================

FICHIERS À DÉPLOYER SUR HOSTINGER
----------------------------------
Déposer dans public_html/ (ou le dossier racine de votre domaine) :

  decizif.html        → Page principale
  decizif.css         → Styles
  contact.php         → Script d'envoi email  ← CONFIGURER AVANT
  PHPMailer/          → Dossier bibliothèque  ← TÉLÉCHARGER (voir ci-dessous)
  llms.txt            → Fiche IA (optionnel)


ÉTAPE 1 — Télécharger PHPMailer
--------------------------------
Télécharger depuis GitHub :
https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip

Extraire l'archive et copier UNIQUEMENT le dossier "src" dans un dossier "PHPMailer" :

  PHPMailer/
    src/
      Exception.php
      PHPMailer.php
      SMTP.php

Structure finale sur le serveur :
  public_html/
    PHPMailer/
      src/
        Exception.php
        PHPMailer.php
        SMTP.php
    contact.php
    decizif.html
    decizif.css


ÉTAPE 2 — Configurer contact.php
----------------------------------
Ouvrir contact.php et remplacer à la ligne ~32 :

  $mail->Password = 'TON_MOT_DE_PASSE';

Par le mot de passe de votre boîte mail jfgalles@decizif.fr
(celui que vous utilisez dans le webmail Hostinger)


ÉTAPE 3 — Uploader via FTP ou Gestionnaire de fichiers
--------------------------------------------------------
Dans Hostinger hPanel :
  → Gestionnaire de fichiers → public_html
  → Uploader les fichiers et le dossier PHPMailer/

Ou via FTP (FileZilla) :
  Hôte     : ftp.hostinger.com
  Login    : votre login FTP Hostinger
  Mot passe: votre mot de passe FTP
  Port     : 21


ÉTAPE 4 — Tester
-----------------
1. Aller sur https://www.decizif.fr
2. Remplir le formulaire de contact
3. Vérifier la réception dans votre boîte jfgalles@decizif.fr


RÉSOLUTION DE PROBLÈMES
------------------------
Erreur "Could not authenticate" → Vérifier le mot de passe dans contact.php
Erreur "Connection failed"      → Essayer Port 587 + ENCRYPTION_STARTTLS à la place
Page blanche ou 500             → Activer les erreurs PHP temporairement :
                                   Ajouter en haut de contact.php :
                                   ini_set('display_errors', 1);
                                   error_reporting(E_ALL);

Si port 465 ne fonctionne pas, modifier dans contact.php :
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = 587;


PARAMÈTRES SMTP HOSTINGER (rappel)
------------------------------------
  Serveur  : smtp.hostinger.com
  Port SSL : 465
  Port TLS : 587
  Login    : jfgalles@decizif.fr
  Password : [votre mot de passe]


==============================================
