<?php
session_start();
if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
    header("Location: login.php");
    exit();
}

require_once("fonctions.php");
require_once("bd.php");

$namError = $mailError = $imageError = $mod_error = "";
$mod_image = "";
$ok = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_SESSION["type_admin"] == 2) {
        $mod_error = "Seuls les super admins sont autorisés à modifier les instructeurs.";
    } else {
        $ok = true;

        // Validation du nom
        if (!empty($_POST["insName"])) {
            $nom_ins = check_input($_POST["insName"]);
        } else {
            $namError = "Veuillez renseigner le nom.";
            $ok = false;
        }

        // Validation de l'email
        if (!empty($_POST["insEmail"])) {
            if (is_email($_POST["insEmail"])) {
                $email_ins = check_input($_POST["insEmail"]);
            } else {
                $mailError = "Entrez un email valide.";
                $ok = false;
            }
        } else {
            $mailError = "Veuillez renseigner l'email.";
            $ok = false;
        }

        // Validation des autres champs
        $phone_ins = !empty($_POST["phone_ins"]) ? check_input($_POST["phone_ins"]) : "";
        $qualification = !empty($_POST["qualification"]) ? check_input($_POST["qualification"]) : "";
        $description = !empty($_POST["description"]) ? check_input($_POST["description"]) : "";

        if (empty($phone_ins) || empty($qualification) || empty($description)) {
            $ok = false;
        }

        // Validation de l'image
        if (!empty($_FILES["profilePic"]["name"])) {
            $target_dir = "images/";
            $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
            if ($check !== false) {
                if (in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                    $newfilename = round(microtime(true)) . '.' . $imageFileType;
                    $newfilepath = $target_dir . $newfilename;
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $newfilepath)) {
                        $mod_image = $newfilename;
                    } else {
                        $imageError = "Erreur lors du téléchargement de l'image.";
                        $ok = false;
                    }
                } else {
                    $imageError = "Seuls les formats JPG, JPEG, PNG et GIF sont autorisés.";
                    $ok = false;
                }
            } else {
                $imageError = "Le fichier n'est pas une image valide.";
                $ok = false;
            }
        }

        // Mise à jour des informations
        if ($ok) {
            try {
                if ($ok) {
                    $sql = $connect->prepare(
                        "INSERT INTO instructeurs (nom_ins, email_ins , phone_ins , photo_ins , qualification_ins , description_ins ) VALUES(?,?,?,?,?,?)"
                    );
                    $sql->execute([$nom_ins, $email_ins, $phone_ins, $mod_image, $qualification, $description]);
                }
                header("Location: instructeur.php");
                exit();
            } catch (Exception $e) {
                $mod_error = "Erreur lors de la création de l'instructeur : " . $e->getMessage();
            }
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
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">instructeurs</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Les instructeurs</h6>
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
                                <h6>modifier un instructeur</h6>
                                <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($mod_error) && !empty($mod_error)) {
                                                                                                        echo $mod_error;
                                                                                                    } ?></p>

                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="mx-3">
                                    <?php if (!empty($mod_error)) : ?>
                                        <div class="alert alert-danger"> <?= $mod_error ?> </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="insName">Nom</label>
                                        <input type="text" name="insName" class="form-control" value="<?php if(isset($nom_ins) && !empty($nom_ins)) echo $nom_ins; ?>">
                                        <span class="text-danger"> <?= $namError ?> </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="insEmail">Email</label>
                                        <input type="email" name="insEmail" class="form-control" value="<?php if(isset($email_ins) && !empty($email_ins)) echo $email_ins; ?>">
                                        <span class="text-danger"> <?= $mailError ?> </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone_ins">Numéro de téléphone</label>
                                        <input type="text" name="phone_ins" class="form-control" value="<?php if(isset($phone_ins) && !empty($phone_ins)) echo $phone_ins; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="qualification">Qualification</label>
                                        <input type="text" name="qualification" class="form-control" value="<?php if(isset($qualification) && !empty($qualification)) echo $qualification; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control"> <?php if(isset($description) && !empty($description)) echo $description; ?> </textarea>
                                    </div>

                                    <div class="custom-file form-group ">
                                        <label for="profilePic" class="my-0">Photo de profil</label>
                                        <input type="file" class="custom-file-input form-control" id="profilePic" name="profilePic" aria-describedby="inputGroupFileAddon01" onchange="updateFileName(this)">
                                        <label class="custom-file-label" for="profilePic">choisir une photo</label>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold"><?php if(isset($imageError) && !empty($imageError)){ echo $imageError;} ?></p>
                                        <p><?php if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]))  echo $_FILES["profilePic"]["name"]; ?>
                                        </p>

                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-warning form-control"><span class="fas fa-pencil mx-2"></span>Modifier</button>
                                        </div>
                                        <div class="col-md-6">
                                            <button href="instructeur.php" class="btn btn-outline-secondary form-control"><span class="fas fa-cancel mx-2"></span>Annuler</button>
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