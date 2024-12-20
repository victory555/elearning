<?php
session_start();
if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
  header("Location: login.php");
  exit();
}
require_once("fonctions.php");
require_once("bd.php");

$mod_image = $mod_password = $passwordError1 = $passwordError2 = $namError = $mailError = $imageError = "";
$mod_ok = false;

if (isset($_GET["id_admin"]) && !empty($_GET["id_admin"])) {
    $id_admin = $_GET["id_admin"];
    $requete = $connect->prepare("SELECT * FROM administrateurs WHERE id_admin=?");
    $requete->execute(array($id_admin));
    $result = $requete->fetch();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["adminId"]) && !empty($_POST["adminId"])) {
    $id_admin = check_input($_POST["adminId"]);
}
  if($_SESSION["type_admin"]==2 && $_SESSION["id_admin"]!=$id_admin){
    $mod_error="Seuls les super Admins sont autorisés à faire des modifs, vous ne pouvez modifier que votre profil <span class=\" fas fa-exclamation-circle\"></span>";
  }else{
    
  
  $mod_ok = true;



    // Validation du nom
    if (isset($_POST["adminName"]) && !empty($_POST["adminName"])) {
        $nom_admin = check_input($_POST["adminName"]);
    } else {
        $namError = 'Veuillez renseigner le nom';
        $mod_ok = false;
    }

    // Validation de l'email
    if (isset($_POST["adminEmail"]) && !empty($_POST["adminEmail"])) {
        if (is_email($_POST["adminEmail"])) {
            $email_admin = check_input($_POST["adminEmail"]);
        } else {
            $mailError = 'Entrer un email conforme';
            $mod_ok = false;
        }
    } else {
        $mailError = 'Veuillez renseigner l\'email';
        $mod_ok = false;
    }

    // Validation du type d'admin
    if (isset($_POST["adminType"]) && !empty($_POST["adminType"])) {
        $type_admin = check_input($_POST["adminType"]);
    } else {
        $mod_ok = false;
    }

    // Validation du mot de passe
    if (isset($_POST["password1"]) && !empty($_POST["password1"])) {
        $password1 = check_input($_POST["password1"]);
        if (isset($_POST["password2"]) && !empty($_POST["password2"])) {
            $password2 = check_input($_POST["password2"]);
            if ($password1 == $password2) {
                $password = password_hash($password1, PASSWORD_DEFAULT);
                $mod_password = true;
            } else {
                $passwordError2 = 'Les mots de passe ne concordent pas';
                $mod_ok = false;
            }
        } else {
            $passwordError2 = 'Veuillez confirmer le mot de passe';
            $mod_ok = false;
        }
    }

    // Validation de l'image
    if (isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Vérification de l'image
        $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = true;
        } else {
            $imageError = 'Le fichier n\'est pas une image';
            $mod_ok = false;
        }

        // Vérification des types de fichiers
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            $imageError = 'Seuls les formats JPG, JPEG, PNG & GIF sont autorisés';
            $mod_ok = false;
        }

        if ($mod_ok) {
            $newfilename = round(microtime(true)) . '.' . $imageFileType;
            $newfilepath = $target_dir . $newfilename;
            if (!move_uploaded_file($_FILES["profilePic"]["tmp_name"], $newfilepath)) {
                $imageError = 'Erreur lors du téléchargement de l\'image';
                $mod_ok = false;
            } else {
                $mod_image = true;
            }
        }
    }

    // Mise à jour des informations dans la base de données
    if ($mod_ok) {
        if ($mod_password && $mod_image) {
            $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=?, password_admin=?, photo_admin=? WHERE id_admin=?");
            $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $password, $newfilename, $id_admin));
            header("Location:index.php");
        } elseif ($mod_password) {
            $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=?, password_admin=? WHERE id_admin=?");
            $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $password, $id_admin));
            header("Location:index.php");

        } elseif ($mod_image) {
            $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=?, photo_admin=? WHERE id_admin=?");
            $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $newfilename, $id_admin));
            header("Location:index.php");

        } else {
            $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=? WHERE id_admin=?");
            $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $id_admin));
            header("Location:index.php");

        }
        exit();
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title></title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="./elements/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./elements/css/nucleo-svg.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="shortcut icon" href="images/EDU header.png" type="image/x-icon">
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
                                <h6>modifier un administrateur</h6>
                                <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($mod_error) && !empty($mod_error)){ echo $mod_error;} ?></p>

                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-3" enctype="multipart/form-data">
                                    <div class="form-group my-1">
                                        <input type="hidden" class="form-control" id="adminId" name="adminId" value="<?php if(isset($result["id_admin"]) && !empty($result["id_admin"])){ echo $result["id_admin"];}else if(isset($id_admin) && !empty($id_admin)){ echo $id_admin;} ?>">
                                        <label for="adminName" class="my-0">Nom</label>
                                        <input type="text" class="form-control" id="adminName" name="adminName" value="<?php if(isset($result["nom_admin"]) && !empty($result["nom_admin"])){ echo $result["nom_admin"];} else if(isset($nom_admin) && !empty($nom_admin)){ echo $nom_admin;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($namError) && !empty($namError)){ echo $namError;} ?></p>

                                    </div>
                                    <div class="form-group">
                                        <label for="adminEmail" class="my-0">Email</label>
                                        <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="<?php if(isset($result["email_admin"]) && !empty($result["email_admin"])){ echo $result["email_admin"];} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($mailError) && !empty($mailError)){ echo $mailError;} ?></p>
                                    </div>
                                    <div class="form-group ">
                                        <label for="adminType" class="my-0">Type</label>
                                        <select class="form-control" id="adminType" name="adminType" >
                                            <option value="1" <?php if(isset($result["type_admin"]) && !empty($result["type_admin"]) && $result["type_admin"]==1 ){ echo "selected";} ?>>Super Admin</option>
                                            <option value="2" <?php if(isset($result["type_admin"]) && !empty($result["type_admin"]) && $result["type_admin"]==2){ echo "selected";} ?>>Simple Admin</option>
                                        </select>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($typeError) && !empty($typeError)){ echo $typeError;} ?></p>
                                    </div>
                                    <div class="form-group ">
                                        <label for="password1" class="my-0">Mot de passe</label>
                                        <input type="password" class="form-control" id="password1" name="password1" value="<?php if(isset($password1) && !empty($password1)){ echo $password1;} ?>" placeholder="Si vous voulez changer">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($passwordError1) && !empty($passwordError1)){ echo $passwordError1;} ?></p>
                                    </div>                                    
                                    <div class="form-group ">
                                        <label for="password2" class="my-0">Confirmer Mot de passe</label>
                                        <input type="password" class="form-control" id="password2" name="password2" value="<?php if(isset($password2) && !empty($password2)){ echo $password2;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($passwordError2) && !empty($passwordError2)){ echo $passwordError2;} ?></p>
                                    </div>     
                                    <div class="custom-file form-group mb-2">
                                      <div class="row mx-2">
                                        <div class="col-md-6">
                                        <label for="profilePic" class="my-0">Photo de profil</label>
                                        <input type="file" class="custom-file-input form-control" id="profilePic" name="profilePic" aria-describedby="inputGroupFileAddon01" onchange="updateFileName(this)">
                                        <label class="custom-file-label" for="profilePic">changer la photo</label>
                                        </div>
                                        <div class="col-md-6">
                                          <img src="images/<?php if(isset($result["photo_admin"]) && !empty($result["photo_admin"])){ echo $result["photo_admin"];} ?>" alt="ancien photo de profil" srcset="" class="img-thumbnail img-fluid rounded-pill" style="height: 60px;">                                          
                                          
                                        </div>
                                      </div>

                                        <p class="text-danger" style="font-size: 10px; font-weight: bold"><?php if(isset($imageError) && !empty($imageError)){ echo $imageError;} ?></p>
                                        <p><?php if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]))  echo $_FILES["profilePic"]["name"]; ?>
                                        </p>

                                    </div> 
                                    <div class="row mt-3">
                                      <div class="col-md-6">
                                      <button type="submit" class="btn btn-warning  form-control">Modifier <span class="fas fa-pencil mx-3"></span></button>
                                      </div>
                                      <div class="col-md-6">
                                        <a href="index.php" class="btn btn-secondary form-control">Annuler <span class="fas fa-arrow-left mx-3"></span></a>
                                      </div>
                                    </div>            
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