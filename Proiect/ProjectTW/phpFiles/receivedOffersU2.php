<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['email'])) {
    header("Location: http://localhost/ProjectTW/phpFiles/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usernames']) && is_array($_POST['usernames'])) 
    {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            $accepted_emails = implode(',', $_POST['usernames']);

            $update_sql1 = "
                UPDATE users 
                SET accepted_offers = 
                    CASE 
                        WHEN accepted_offers IS NULL OR accepted_offers = '' THEN :accepted_emails 
                        ELSE accepted_offers || ',' || :accepted_emails 
                    END 
                WHERE email = :email";
            
            $stmt_update1 = $pdo->prepare($update_sql1);
            $stmt_update1->bindParam(':accepted_emails', $accepted_emails);
            $stmt_update1->bindParam(':email', $_SESSION['email']);
            $stmt_update1->execute();

            $placeholders = [];
            $myEmail = $_SESSION['email'];
            foreach ($_POST['usernames'] as $index => $email) {
                $placeholders[] = ':email' . $index;
            }

            $sql = "
                UPDATE companies 
                SET accepted_offers = 
                    CASE 
                        WHEN accepted_offers IS NULL OR accepted_offers = '' THEN :myEmail 
                        ELSE accepted_offers || ',' || :myEmail 
                    END 
                WHERE email IN (" . implode(',', $placeholders) . ")";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':myEmail', $myEmail);
            foreach ($_POST['usernames'] as $index => $email) {
                $stmt->bindParam(':email' . $index, $email);
            }

            $stmt->execute();


            if ($stmt_update1 && $stmt) 
            {
                header("Location: http://localhost/ProjectTW/htmlFiles/receivedOffersUmessage.html");
                exit();
            }
             else {
                echo "Error updating users.";
            }

            $stmt_update1->closeCursor();
            $pdo = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No users were selected.";
    }
} else {
    echo "Invalid request method.";
}

?>
