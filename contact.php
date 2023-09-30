<?php
require_once 'vendor/autoload.php'; // Inclure l'autoloader de SwiftMailer

// Connexion à la base de données
$servername = "localhost";
$username = "votre_nom_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "sa_poseur";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupération des données du formulaire
$nom = $_POST['nom'];
$email = $_POST['email'];
$message = $_POST['message'];

// Insertion du message dans la base de données
$sql = "INSERT INTO messages (nom, email, message) VALUES ('$nom', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    // Configuration de SwiftMailer
    $transport = (new Swift_SmtpTransport('smtp.example.com', 587))
        ->setUsername('votre_adresse_email')
        ->setPassword('votre_mot_de_passe');

    $mailer = new Swift_Mailer($transport);

    // Création de l'e-mail
    $message_email = (new Swift_Message('Nouveau message de ' . $nom))
        ->setFrom(['votre_adresse_email' => 'Votre nom'])
        ->setTo(['sa.poseur83@hotmail.com' => 'SA-POSEUR'])
        ->setBody("Nom : $nom\nEmail : $email\nMessage :\n$message");

    // Envoi de l'e-mail
    $result = $mailer->send($message_email);

    if ($result) {
        echo "Message envoyé avec succès.";
    } else {
        echo "Erreur lors de l'envoi de l'e-mail.";
    }
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
