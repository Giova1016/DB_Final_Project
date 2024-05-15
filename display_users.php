<?php
include("database.php");

// Handle form submission for adding/editing Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $birthday = filter_input(INPUT_POST, "birthday", FILTER_SANITIZE_SPECIAL_CHARS);
    $direction = filter_input(INPUT_POST, "direction", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($name) || empty($birthday) || empty($direction) || empty($email) || empty($password)) {
        echo "Please fill all the fields.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if (empty($userId)) {
            // Add new Usuario
            $sql = "INSERT INTO Usuario (NombreC, FechaNac, Direccion, Email, Password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $birthday, $direction, $email, $hash);
        } else {
            // Update existing Usuario
            $sql = "UPDATE Usuario SET NombreC = ?, FechaNac = ?, Direccion = ?, Email = ?, Password = ? WHERE Id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $birthday, $direction, $email, $hash, $userId);
        }

        if ($stmt->execute()) {
            echo "Record successfully saved.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Prepare for editing
$usuario = null;
if (isset($_GET['edit'])) {
    $userId = $_GET['edit'];
    $sql = "SELECT * FROM Usuario WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <!-- Bootstrap CSS -->
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Users</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // Check if the database name is provided as a query parameter
            if (isset($_GET['database'])) {
                // SQL query to retrieve Usuario data
                $sql = "SELECT * FROM Usuario";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<table class='table'>";
                    echo "<thead><tr><th>ID</th><th>Name</th><th>BirthDay</th><th>Direction</th><th>Email</th><th>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["Id"] . "</td><td>" . $row["NombreC"] . "</td><td>" . $row["FechaNac"] . "</td><td>" . $row["Direccion"] . "</td><td>" . $row["Email"] . "</td>";
                        echo "<td><a href='display_users.php?edit=" . $row["Id"] . "#form'>Edit</a></td></tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "0 results";
                }

                // Close connection
                $conn->close();
            } else {
                echo "No database selected.";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($usuario) ? "Edit User" : "Add New User"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="userId" value="<?php echo isset($usuario) ? $usuario['Id'] : ''; ?>" class="form-control">
        Full Name:<br>
        <input type="text" name="name" value="<?php echo isset($usuario) ? $usuario['NombreC'] : ''; ?>" class="form-control"><br>
        Birth Date:<br>
        <input type="text" name="birthday" value="<?php echo isset($usuario) ? $usuario['FechaNac'] : ''; ?>" class="form-control"><br>
        Direction:<br>
        <input type="text" name="direction" value="<?php echo isset($usuario) ? $usuario['Direccion'] : ''; ?>" class="form-control"><br>
        Email:<br>
        <input type="text" name="email" value="<?php echo isset($usuario) ? $usuario['Email'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($usuario) ? "Update" : "Add"; ?>"class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
