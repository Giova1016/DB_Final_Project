<?php
include("database.php");

// Handle form submission for adding/editing Fruta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bebidaId = filter_input(INPUT_POST, "bebidaId", FILTER_SANITIZE_NUMBER_INT);
    $nombreFruta = filter_input(INPUT_POST, "nombreFruta", FILTER_SANITIZE_SPECIAL_CHARS);
    $frutaId = filter_input(INPUT_POST, "frutaId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($bebidaId) || empty($nombreFruta)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($frutaId)) {
            // Add new Fruta
            $sql = "INSERT INTO Frutas (BebidaId, NombreFruta) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $bebidaId, $nombreFruta);
        } else {
            // Update existing Fruta
            $sql = "UPDATE Frutas SET BebidaId = ?, NombreFruta = ? WHERE FrutaId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $bebidaId, $nombreFruta, $frutaId);
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
$fruta = null;
if (isset($_GET['edit'])) {
    $frutaId = $_GET['edit'];
    $sql = "SELECT * FROM Frutas WHERE FrutaId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $frutaId);
    $stmt->execute();
    $result = $stmt->get_result();
    $fruta = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Frutas</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Frutas</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Frutas data
            $sql = "SELECT * FROM Frutas";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Fruta ID</th><th>Bebida ID</th><th>Nombre Fruta</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["FrutaId"] . "</td><td>" . $row["BebidaId"] . "</td><td>" . $row["NombreFruta"] . "</td>";
                    echo "<td><a href='?edit=" . $row["FrutaId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($fruta) ? "Edit Fruta" : "Add New Fruta"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="frutaId" value="<?php echo isset($fruta) ? $fruta['FrutaId'] : ''; ?>">
        Bebida ID:<br>
        <input type="text" name="bebidaId" value="<?php echo isset($fruta) ? $fruta['BebidaId'] : ''; ?>" class="form-control"><br>
        Nombre Fruta:<br>
        <input type="text" name="nombreFruta" value="<?php echo isset($fruta) ? $fruta['NombreFruta'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($fruta) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
