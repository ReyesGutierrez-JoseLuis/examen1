<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5">Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Bienvenido al administrador.</h1>
    
    <p><div><?php echo htmlspecialchars($_SESSION["nombre"]); ?></div>
    <br>
        <a href="reset-password.php" class="btn btn-warning">Cambia tu contraseña</a>
        <a href="logout.php" class="btn btn-danger ml-3">Cerrar sesión</a>
    </p>

    <div class="form-group">
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div>
            <span>Sube una foto de perfil</span>
            <input type="file" name="uploadedFile" />
            <br>
            <br>
            </div>
            <input type="submit" name="uploadBtn" value="Upload" />
        </form>
    </div> 
        <?php
        if (htmlspecialchars($_SESSION["rol"]) == "admin") {
            echo '<table class="table table-borderless table-data3">';
            echo "<thead><tr><th>Id</th><th>CURP</th><th>Nombre</th><th>Apellido</th><th>Telefono</th><th>Direccion</th><th>Email</th><th>Username</th></tr></thead>";

            class TableRows extends RecursiveIteratorIterator
            {
                function __construct($it)
                {
                    parent::__construct($it, self::LEAVES_ONLY);
                }

                function current()
                {
                    return "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
                }

                function beginChildren()
                {
                    echo "<tr>";
                }

                function endChildren()
                {
                    echo "</tr>" . "\n";
                }
            }

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "examen_login";
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("SELECT id, curp,nombre, apellido, telefono, direccion, correo, username FROM users");
                $stmt->execute();

                // set the resulting array to associative
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
                    echo $v;
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
            echo "</table></div></div></div></div></div>";
        }
        ?>
</body>
</html>