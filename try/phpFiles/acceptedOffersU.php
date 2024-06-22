<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "Eroare: Utilizatorul nu este autentificat.";
    exit();
}

// Detalii de conexiune la baza de date
$host = 'localhost';
$dbname = 'try';
$user = 'postgres';
$password = 'euro2024';

try {
    // Conectare la baza de date PostgreSQL
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $myEmail = $_SESSION['email'];
    $query = "SELECT accepted_offers FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $myEmail);

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the single row as associative array

    if ($result && isset($result['accepted_offers'])) {
        $emails = explode(',', $result['accepted_offers']);
        foreach ($emails as $email) {
            $sql = "SELECT * FROM companies WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $company = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($company) {
                echo "Name: " . $company['companyname'] . "<br>";
                echo "Email: " . $company['email'] . "<br>";
                echo "City: " . $company['city'] . "<br>";
                echo "About us: " . $company['about_us'] . "<br>";
                echo "Phone: " . $company['phone'] . "<br>";
                echo "<br><br>";
            } else {
                echo "Nu s-a găsit nicio companie pentru emailul: " . $email . "<br>";
            }
        }
    } else {
        echo "Nu s-a găsit nicio înregistrare care să îndeplinească criteriile specificate.";
    }

} catch (PDOException $e) {
    echo "Eroare la conectarea la baza de date: " . $e->getMessage();
}
?>
