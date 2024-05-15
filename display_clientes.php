<?php
include("database.php");

// Handle form submission for adding/editing Cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioId = filter_input(INPUT_POST, "usuarioId", FILTER_SANITIZE_NUMBER_INT);
    $visita = filter_input(INPUT_POST, "visita", FILTER_SANITIZE_SPECIAL_CHARS);
    $negocioId = filter_input(INPUT_POST, "negocioId", FILTER_SANITIZE_NUMBER_INT);
    $clienteId = filter_input(INPUT_POST, "clienteId", FILTER_SANITIZE_NUMBER_INT);

    if (empty($usuarioId) || empty($visita) || empty($negocioId)) {
        echo "Please fill all the fields.";
    } else {
        if (empty($clienteId)) {
            // Add new Cliente
            $sql = "INSERT INTO Cliente (UsuarioId, Visita, NegocioId) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $usuarioId, $visita, $negocioId);
        } else {
            // Update existing Cliente
            $sql = "UPDATE Cliente SET UsuarioId = ?, Visita = ?, NegocioId = ? WHERE ClienteId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $usuarioId, $visita, $negocioId, $clienteId);
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
$cliente = null;
if (isset($_GET['edit'])) {
    $clienteId = $_GET['edit'];
    $sql = "SELECT * FROM Cliente WHERE ClienteId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $clienteId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Clientes</title>
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Clientes</h2>
    <div class="row mt-3">
        <div class="col">
            <?php
            // SQL query to retrieve Cliente data
            $sql = "SELECT * FROM Cliente";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead><tr><th>Cliente ID</th><th>Usuario ID</th><th>Visita</th><th>Negocio ID</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["ClienteId"] . "</td><td>" . $row["UsuarioId"] . "</td><td>" . $row["Visita"] . "</td><td>" . $row["NegocioId"] . "</td>";
                    echo "<td><a href='?edit=" . $row["ClienteId"] . "#form'>Edit</a></td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>

    <h2 id="form"><?php echo isset($cliente) ? "Edit Cliente" : "Add New Cliente"; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="clienteId" value="<?php echo isset($cliente) ? $cliente['ClienteId'] : ''; ?>">
        Usuario ID:<br>
        <input type="text" name="usuarioId" value="<?php echo isset($cliente) ? $cliente['UsuarioId'] : ''; ?>" class="form-control"><br>
        Visita:<br>
        <input type="text" name="visita" value="<?php echo isset($cliente) ? $cliente['Visita'] : ''; ?>" class="form-control"><br>
        Negocio ID:<br>
        <input type="text" name="negocioId" value="<?php echo isset($cliente) ? $cliente['NegocioId'] : ''; ?>" class="form-control"><br>
        <input type="submit" value="<?php echo isset($cliente) ? "Update" : "Add"; ?>" class="btn btn-primary mt-2">
    </form>
</div>

</body>
</html>
