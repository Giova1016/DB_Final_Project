<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>SQL Query</title>
</head>
<body>

    <h2>Choose a Predefined Query or Enter Your Own:</h2>
    <!-- Predefined query buttons -->
    <div class="btn-containerquery"> 
        <button class="btn btn-primary mt-2" onclick="submitQuery('SELECT DuenoNegocio.DueñoId, NombreC, FechaNac, Email, NombreNegocio, MenuBebidas FROM DuenoNegocio INNER JOIN Usuario ON DuenoNegocio.UsuarioId = Usuario.Id INNER JOIN Negocio ON DuenoNegocio.DueñoId = Negocio.DueñoId ORDER BY DuenoNegocio.DueñoId')">Usuarios que tienen Negocio</button>
        <button class="btn btn-primary mt-2" onclick="submitQuery('SELECT Negocio.NegocioId, NombreNegocio, NombreBebida, Descuento FROM Negocio INNER JOIN Bebidas ON Negocio.NegocioId = Bebidas.NegocioId INNER JOIN Promociones ON Bebidas.BebidasId = Promociones.BebidasId')">Negocios que tienen ofertas en bebidas</button>
        <button class="btn btn-primary mt-2" onclick="submitQuery('SELECT Negocio.NombreNegocio, Inventario.Descripcion, Inventario.Cantidad FROM Negocio INNER JOIN Inventario ON Negocio.NegocioId = Inventario.NegocioId;')">Inventario de Negocios</button>
    </div>
    
    <!-- Form for custom query -->
    <form id="queryForm" action="execute_query.php" method="post">
        <textarea  name="query" rows="5" cols="50" class="querytext" ></textarea><br>
        <input type="submit" value="Submit"class="btn btn-primary mt-2">
    </form>

    <!-- JavaScript function to submit the predefined queries -->
    <script>
        function submitQuery(query) {
            document.getElementById("queryForm").querySelector("textarea").value = query;
            document.getElementById("queryForm").submit();
        }
    </script>
</body>
</html>
