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
      <script src="https://kit.fontawesome.com/3ec2760d3c.js" crossorigin="anonymous"></script>
    <style>
        body{ font: 14px sans-serif; text-align: center; }
        .header {display: flex; justify-content: space-around; height: 80px; width: 100%; background-color: black; top: 0; padding: 0px 40px;}
        .header .links {padding:30px 40px 0px 0px;}
        .header .links a {padding-left:20px; color:#fff;}
        .header p {color:#FFF; padding-top:30px; justify-content: flex-start;}
        .header img { padding: 0; margin:0; height: 100%; vertical-align:top; -webkit-animation: 3s rotate linear infinite; animation: 3s rotate linear infinite; -webkit-transform-origin: 50% 50%; transform-origin: 50% 50%;}
        @keyframes rotate {from {transform: rotate(0deg);}
        to {transform: rotate(360deg);}}
        @-webkit-keyframes rotate {from {-webkit-transform: rotate(0deg);}
        to {-webkit-transform: rotate(360deg);}}
        footer{   background-color: black; position: absolute; bottom: 0; width: 100%; height: 40px; color: white; }
        footer .social-media { text-align:center; color:#fff; padding-top:10px;}
        footer .social-media i {font-size:20px; padding-right:15px;}
        footer .social-media i:hover {color:blue;}
    </style>
</head>
<body>

    <div class="header">
        <img src="img/icon.png" title="TGH">
        <p>HIGH TECH</p>
        <div class="links">
        <a href="welcome.php">Welcome <i class="fas fa-door-open"></i></a>
        <a href="reset-password.php">Reset password <i class="fas fa-window-restore"></i></a>
        <a href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

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
    <footer>
        <div class="social-media">
            <i class="fab fa-facebook-f"></i>
            <i class="fab fa-twitter"></i>
            <i class="fab fa-instagram"></i>
            <i class="fab fa-youtube"></i>
        </div>
    </footer>
</body>
</body>
</html>