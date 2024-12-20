<?php
session_start();
require_once("fonctions.php");
require_once("bd.php");
$email = $password = $emailError = $passwordError = "";
$ok_email = $ok_password = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && !empty($_POST["email"])) {
        $email = $_POST["email"];
        if (is_email($email)) {
            $ok_email = true;
        } else {
            $ok_email = false;
            $emailError = 'Ceci n\'est pas un email <span class="fas fa-circle-exclamation"></span>';
        }
    } else {
        $emailError = 'Veuillez remplir le champs email <span class="fas fa-circle-exclamation"></span>';
        $ok_email = false;
    }

    if (isset($_POST["password"]) && !empty($_POST["password"])) {
        $password = $_POST["password"];
        $ok_password = true;
    } else {
        $passwordError = 'Veuillez remplir le champs password <span class="fas fa-circle-exclamation"></span>';
        $ok_password = false;
    }

    if ($ok_email && $ok_password) {
        $query = $connect->prepare( "SELECT * FROM administrateurs WHERE email_admin = ?");
        $query->execute(array($email));
        $result = $query->fetch();

        if ($result) {
            $id_admin = $result["id_admin"];
            $password_admin = $result["password_admin"];
            $nom_admin = $result["nom_admin"];
            if (password_verify($password, $password_admin)) {
                $_SESSION["id_admin"] = $id_admin;
                $_SESSION["nom_admin"] = $nom_admin;
                $_SESSION["email_admin"] = $email;
                $_SESSION["type_admin"]=$result["type_admin"];
                header("location:index.php");
                exit();
            } else {
                $passwordError = 'Mot de passe incorrect <span class="fas fa-circle-exclamation"></span>';
            }
        } else {
            $emailError = 'Ce email n\'existe pas <span class="fas fa-circle-exclamation"></span>';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/fav32.png">
    <link rel="stylesheet" href="../css/all.min.css">
    <script src="../js/all.min.js"></script>
    <title>Page de connexion</title>
</head>
<body>
<section class="form-08">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="_form-08-main" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="_form-08-head">
                        <div>
                            <span class="fas fa-circle-user" style="color: rgb(0, 64, 240);font-size: 50px;"></span>
                        </div>
                        <h2>Veuillez entrer vos informations</h2>
                    </div>
                    <div class="form-group">
                        <label>votre Email <span class="mx-1 fas fa-envelope"></span></label>
                        <input type="email" name="email" class="form-control rounded-pill" placeholder="votre Email"  value="<?php if(isset($email) && !empty($email)){ echo $email; }?>">
                        <p class="text-danger" style="font-size: 12px;"><?php if(isset($emailError) && !empty($emailError)){ echo $emailError; }?></p>
                    </div>
                    <div class="form-group">
                        <label>mot de passe <span class="mx-1 fas fa-lock"></span></label>
                        <input type="password" name="password" class="form-control rounded-pill" placeholder="votre Password" value="<?php if(isset($password) && !empty($password)){ echo $password; }?>">
                        <p class="text-danger" style="font-size: 12px;"><?php if(isset($passwordError) && !empty($passwordError)){ echo $passwordError; }?></p>
                    </div>
                    <div class="form-group">
                        <button class="form-control btn btn-success rounded-pill" type="submit">Se connecter <span class="fas fa-arrow-right-from-bracket mx-2"></span></button>
                    </div>
                    <div class="sub-01">
                        <img src="assets/images/shap-02.png">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</script>
</body>
</html>

