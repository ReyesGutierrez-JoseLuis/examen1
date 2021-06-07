<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

$rol = $correo = $nombre = $telefono = $apellido = $direccion = $fecha_admin = $CURP = "";
$rol_err = $correo_err = $nombre_err = $telefono_err = $apellido_err = $direccion_err = $fechaAdmin_err = $CURP_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validar correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Porfavor incluir un correo electronico";
    } elseif (!preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', trim($_POST["correo"]))) {
        $correo_err = "Porfavor incluir un correo electronico valido";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE correo = :correo";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);

            // Set parameters
            $param_correo = trim($_POST["correo"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $correo_err = "Este correo electronico ya existe";
                } else {
                    $correo = trim($_POST["correo"]);
                    $correo = filter_var($correo, FILTER_VALIDATE_EMAIL);
                    if ($correo === false) {
                        exit('Invalid Email');
                    }
                }
            } else {
                echo "Algo salio mal. Porfavor intentalo mas tarde.";
            }

            // Close statement
            unset($stmt);
        }
    }


    // Validar curp
    if (empty(trim($_POST["curp"]))) {
        $CURP_err = "Porfavor incluir tu CURP";
    } elseif (!preg_match('/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/', trim($_POST["curp"]))) {
        $CURP_err = "Porfavor incluir un CURP valido";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE curp = :curp";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":curp", $param_curp, PDO::PARAM_STR);

            // Set parameters
            $param_curp = trim($_POST["curp"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $CURP_err = "Este CURP ya esta registrado";
                } else {
                    $CURP = trim($_POST["curp"]);
                }
            } else {
                echo "Algo salio mal. Porfavor intentalo mas tarde.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validar telefono
    if (empty(trim($_POST["telefono"]))) {
        $telefono_err = "Porfavor incluir un numero de telefono";
    } elseif (!preg_match('/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/', trim($_POST["telefono"]))) {
        $telefono_err = "Porfavor incluir un numero telefonico valido";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE telefono = :telefono";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":telefono", $param_telefono, PDO::PARAM_STR);

            // Set parameters
            $param_telefono = trim($_POST["telefono"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $telefono_err = "Este numero ya esta registrado";
                } else {
                    $telefono = trim($_POST["telefono"]);
                    if (preg_match('^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$', $telefono)) {
                        $telefono_err = "Porfavor incluir un numero telefonico valido!";
                    }
                }
            } else {
                echo "Algo salio mal. Porfavor intentalo mas tarde.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // validar nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Porfavor incluya su nombre";
    } elseif (strlen(trim($_POST["nombre"])) > 20) {
        $nombre_err = "El nombre no debe superar los 20 caracteres.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Validar apellido
    if (empty(trim($_POST["apellido"]))) {
        $apellido_err = "Porfavor incluya su apellido";
    } elseif (strlen(trim($_POST["apellido"])) > 30) {
        $apellido_err = "El apellido no debe superar los 30 caracteres.";
    } else {
        $apellido = trim($_POST["apellido"]);
    }

    // Validar direccion
    if (empty(trim($_POST["direccion"]))) {
        $direccion_err = "Porfavor incluya su direccion";
    } elseif (strlen(trim($_POST["direccion"])) > 40) {
        $direccion_err = "La direccion no debe superar los 40 caracteres.";
    } else {
        $direccion = trim($_POST["direccion"]);
    }


    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }


    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($nombre_err) && empty($apellido_err) && empty($telefono_err) && empty($correo_err) && empty($CURP_err) && empty($direccion_err) && empty($rol_err) && empty($fechaAdmin_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (curp, nombre, apellido,telefono, direccion, correo, username, password) VALUES (:curp, :nombre, :apellido, :telefono, :direccion, :correo, :username, :password)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":curp", $param_curp, PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $param_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $param_apellido, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $param_telefono, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $param_direccion, PDO::PARAM_STR);
            $stmt->bindParam(":correo", $param_correo, PDO::PARAM_STR);
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_curp = $CURP;
            $param_nombre = $nombre;
            $param_apellido = $apellido;
            $param_telefono = $telefono;
            $param_direccion = $direccion;
            $param_correo = $correo;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Rellena el formulario para registrarte</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>CURP</label>
                <input type="text" name="curp" class="form-control <?php echo (!empty($CURP_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $CURP; ?>">
                <span class="invalid-feedback"><?php echo $CURP_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                <span class="invalid-feedback"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control <?php echo (!empty($apellido_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $apellido; ?>">
                <span class="invalid-feedback"><?php echo $apellido_err; ?></span>
            </div>
            <div class="form-group">
                <label>Telefono</label>
                <input type="tel" name="telefono" class="form-control <?php echo (!empty($telefono_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $telefono; ?>">
                <span class="invalid-feedback"><?php echo $telefono_err; ?></span>
            </div>
            <div class="form-group">
                <label>Direccion</label>
                <input type="text" name="direccion" class="form-control <?php echo (!empty($direccion_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $direccion; ?>">
                <span class="invalid-feedback"><?php echo $direccion_err; ?></span>
            </div>
            <div class="form-group">
                <label>Correo Electronico</label>
                <input type="email" name="correo" class="form-control <?php echo (!empty($correo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $correo; ?>">
                <span class="invalid-feedback"><?php echo $correo_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nombre de usuario</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirma tu Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <input type="reset" class="btn btn-secondary ml-2" value="Borrar datos">
            </div>
            <p>Tienes una cuenta?<a href="login.php">Inicia sesión</a>.</p>
        </form>
    </div>
</body>

</html>