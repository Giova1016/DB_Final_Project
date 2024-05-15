<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles.css" rel="stylesheet">
    <title>SQL Query</title>
</head>
<body>

    <h2>Choose a Predefined Query or Enter Your Own:</h2>
    <!-- Predefined query buttons -->
    <div class= "querybutton">
    <button onclick="submitQuery('SELECT DuenoNegocio.DueñoId, NombreC, FechaNac, Email, NombreNegocio, MenuBebidas FROM DuenoNegocio INNER JOIN Usuario ON DuenoNegocio.UsuarioId = Usuario.Id INNER JOIN Negocio ON DuenoNegocio.DueñoId = Negocio.DueñoId ORDER BY DuenoNegocio.DueñoId')">Usuarios que tienen Negocio</button>
    <button onclick="submitQuery('SELECT * FROM table2')">Query 2</button>
    <button onclick="submitQuery('SELECT * FROM table3')">Query 3</button>
</div>
    <!-- Form for custom query -->
    <form id="queryForm" action="execute_query.php" method="post">
        <textarea name="query" rows="5" cols="50"></textarea><br>
        <input type="submit" value="Submit">
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