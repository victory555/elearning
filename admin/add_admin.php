<?php
session_start();
if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
    header("Location: login.php");
    exit();
}
$imageError=$namError=$typeError=$mailError=$passwordError1=$passwordError2 ="";
$ok = false;

require_once("fonctions.php");
require_once("bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["adminName"]) && !empty($_POST["adminName"])) {
            $nom_admin = check_input($_POST["adminName"]);
            $ok = true;
        } else {
            $namError = 'Veuillez renseigner le nom <span class="fas fa-circle-exclamation"></span>';
            $ok = false;
        }
        if (isset($_POST["adminType"]) && !empty($_POST["adminType"])) {
            $type_admin = check_input($_POST["adminType"]);
            $ok = true;
        } else {
            $typeError = 'Veuillez renseigner le type <span class="fas fa-circle-exclamation"></span>';
            $ok = false;
        }
        if (isset($_POST["adminEmail"]) && !empty($_POST["adminEmail"])) {
            if (is_email($_POST["adminEmail"])) {
                $email_admin = check_input($_POST["adminEmail"]);
                $ok = true;
            } else {
                $mailError = 'Entrer un email conforme span class="fas fa-circle-exclamation"></span>';
                $ok = false;
            }
        } else {
            $mailError = 'Veuillez renseigner l\'email <span class="fas fa-circle-exclamation"></span>';
            $ok = false;
        }
        if (isset($_POST["password1"]) && !empty($_POST["password1"])) {
            $password1 = check_input($_POST["password1"]);
            $ok = true;
        }else{
            $passwordError1="Veuillez entrer un mot de passe";
            $ok=false;
        }
        if (isset($_POST["password2"]) && !empty($_POST["password2"])) {
            $password2 = check_input($_POST["password2"]);
            if($password1===$password2){
                $ok=true;
                $password=password_hash($password1, PASSWORD_DEFAULT);
            }else{
                $ok=false;
                $passwordError2="les mots de passes ne concordent pas";


            }
           
        }else {
            $passwordError2 = 'Veuillez confirmer le mot de passe <span class="fas fa-circle-exclamation"></span>';
            $ok = false;
        }
        if (isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])) {
            $ok = true;
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
            $uploadOk = true;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            // Vérification de l'image
            $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = true;
                $ok = true;
            } else {
                $imageError = '<b class="text-danger">Le fichier n\'est pas une image.</b>';
                $uploadOk = false;
                $ok = false;
            }

            // Autoriser certains types de fichiers
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $imageError = '<b class="text-danger">Désolé, seuls les formats JPG, JPEG, PNG & GIF sont autorisés <span class="fas fa-circle-exclamation"></span>.</b>';
                $uploadOk = false;
                $ok = false;
            }

            // Enregistrement réussi
            if ($uploadOk) {
                $temp = explode(".", $_FILES["profilePic"]["name"]);
                $newfilename = round(microtime(true)) . '.' . end($temp);
                $newfilepath = $target_dir . $newfilename;

                if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $newfilepath)) {
                    $uploadOk = true;
                }
            }
        }else{
        $imageError="Veuillez choisir une photo de profil";
        $ok=false;
        }
        if ($ok && $uploadOk) {
            $sql = $connect->prepare("INSERT INTO administrateurs( nom_admin, photo_admin, email_admin, type_admin, password_admin) VALUES (?, ?, ?, ?, ?)");
            $requete = $sql->execute(array($nom_admin, $newfilename, $email_admin, $type_admin, $password));
            header("Location: index.php");
            exit();
        }
        

}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edutec l'éducation à portée de clic</title>
    <link rel="shortcut icon" href="images/EDU header.png" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="./elements/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./elements/css/nucleo-svg.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="./elements/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <script src="../js/jquery.min.js"></script>
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <script src="../js/all.min.js"></script>
    <script src="../js/slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/bootstrap.min.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php require_once "navbar.php"; ?>
    <main class="main-content border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">Administrateurs</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Les Administrateurs</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                            <input type="text" class="form-control" id="searchInput" placeholder="Recherche">
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="profil.php" data-toggle="tooltip" data-placement="bottom" title="Mon profil"><span class="fas fa-user-circle"></span></a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="setting.php" data-toggle="tooltip" data-placement="bottom" title="Parametres"> <span class="fas fa-gear"></span></a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="deconnexion.php" data-toggle="tooltip" data-placement="bottom" title="Se deconnecter"> <span class="fas fa-power-off"></span></a>
                        </li>
                        <!-- Add more navbar items here -->
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="container ">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex flex-column justify-content-center">
                                <h1 class="mb-0 text-sm"> Bienvenue <?= $_SESSION['nom_admin'] ?></h1>
                                <p class="text-xs text-secondary mb-0"><?= $_SESSION['email_admin'] ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="text-xs font-weight-bold mb-0">Type: <?= $_SESSION['type_admin'] == 1 ? 'Super Admin' : 'Simple Admin' ?></p>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Ajouter des administrateurs</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-3" enctype="multipart/form-data">
                                    <div class="form-group my-1">
                                        <label for="adminName" class="my-0">Nom</label>
                                        <input type="text" class="form-control" id="adminName" name="adminName" value="<?php if(isset($nom_admin) && !empty($nom_admin)){ echo $nom_admin;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($namError) && !empty($namError)){ echo $namError;} ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="adminEmail" class="my-0">Email</label>
                                        <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="<?php if(isset($email_admin) && !empty($email_admin)){ echo $email_admin;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($mailError) && !empty($mailError)){ echo $mailError;} ?></p>
                                    </div>
                                    <div class="form-group ">
                                        <label for="adminType" class="my-0">Type</label>
                                        <select class="form-control" id="adminType" name="adminType" >
                                            <option value="1">Super Admin</option>
                                            <option value="2">Simple Admin</option>
                                        </select>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($typeError) && !empty($typeError)){ echo $typeError;} ?></p>
                                    </div>
                                    <div class="form-group ">
                                        <label for="password1" class="my-0">Mot de passe</label>
                                        <input type="password" class="form-control" id="password1" name="password1" value="<?php if(isset($password1) && !empty($password1)){ echo $password1;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($passwordError1) && !empty($passwordError1)){ echo $passwordError1;} ?></p>
                                    </div>                                    
                                    <div class="form-group ">
                                        <label for="password2" class="my-0">Confirmer Mot de passe</label>
                                        <input type="password" class="form-control" id="password2" name="password2" value="<?php if(isset($password2) && !empty($password2)){ echo $password2;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($passwordError2) && !empty($passwordError2)){ echo $passwordError2;} ?></p>
                                    </div>     
                                    <div class="custom-file form-group ">
                                        <label for="profilePic" class="my-0">Photo de profil</label>
                                        <input type="file" class="custom-file-input form-control" id="profilePic" name="profilePic" aria-describedby="inputGroupFileAddon01" onchange="updateFileName(this)">
                                        <label class="custom-file-label" for="profilePic">choisir une photo</label>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold"><?php if(isset($imageError) && !empty($imageError)){ echo $imageError;} ?></p>
                                        <p><?php if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]))  echo $_FILES["profilePic"]["name"]; ?>
                                        </p>

                                    </div>             
                                    <button type="submit" class="btn btn-primary  form-control mt-3">Enregistrer <span class="fas fa-save mx-3"></span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="script.js">


    </script>

</body>

</html>