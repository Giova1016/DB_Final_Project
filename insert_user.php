<?php
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Insert New User</h2>
        Full Name:<br>
        <input type="text" name="name"><br>
        Birth Date:<br>
        <input type="text" name="birthday"><br>
        Direction:<br>
        <input type="text" name="direction"><br>
        Email:<br>
        <input type="text" name="email"><br>
        Password:<br>
        <input type="text" name="password"><br>
        <input type="submit" name="Insert" value="register">
    </form>
</body>
</html>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $birthday = filter_input(INPUT_POST, "birthday", FILTER_SANITIZE_SPECIAL_CHARS);
        $direction = filter_input(INPUT_POST, "direction", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($name)){
            echo "Please enter your full name";
        }
        elseif(empty($birthday)){
            echo "Please enter your birthday<br>";
            echo "Format: YYYY-MM-DD";
        }
        elseif(empty($direction)){
            echo "Please enter a direction";
        }
        elseif(empty($email)){
            echo "Please enter an email";
        }
        elseif(empty($password)){
            echo "Please enter a password";
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuario (NombreC, FechaNac, Direccion, Email, Password) VALUES (?, ?, ?, ?, ?)";
            
            try{
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssss", $name, $birthday, $direction, $email, $hash);
                mysqli_stmt_execute($stmt);
                echo "You are now registered";
                mysqli_stmt_close($stmt);
            }
            catch(Exception $e){
                echo "That email is taken";
            }
        }
    }

    mysqli_close($conn);
?>
