<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "eroare modify";
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

    // Preluăm detaliile utilizatorului
    $myName=$_POST['myName'];
    $city=$_POST['city'];
    $phone=$_POST['phone'];
    //$email=$_POST['email'];

    $query = "UPDATE users SET username = :myName, city = :city, phone = :phone WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':myName', $myName);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) 
    {
        echo "Modificare efectuată cu succes pentru utilizatorul cu adresa de email: " . $_SESSION['email'];
    } 
    else 
    {
        echo "Nu s-a efectuat nicio modificare. Verificați datele introduse sau încercați din nou mai târziu.";
    }

} catch (PDOException $e) {
    echo "Eroare la conectarea la baza de date: " . $e->getMessage();
}

?>


