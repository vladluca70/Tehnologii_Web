<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="http://localhost/try/htmlFiles/user.php">Profile</a>
    <a href="http://localhost/try/htmlFiles/modifyUserProfile.php">Modify Profile</a>
    <a href="#">Offerts</a>
    <a href="http://localhost/try/phpFiles/logout.php">Logout</a>
    <br> <br>

    
    <form id="modify" method="POST" action="http://localhost/try/phpFiles/modifyU.php">
        <h2>modify profile</h2>
        
        <label for="myName">My name</label>
        <input type="text" id="myName" name="myName" required>
        <br><br> 

        <label for="city">City</label>
        <input type="text" id="city" name="city" required>
        <br><br>

        <label for="phone">Phone</label>
        <input type="text" id="phone" pattern="\+?[0-9]{10,15}" name="phone" required>
        <br><br> 

        <button type="submit">Update profile</button>
    </form>


</body>
</html>