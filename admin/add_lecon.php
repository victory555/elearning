<?php
session_start();
if (
    !isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) ||
    !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) ||
    !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])
) {
    header("Location: login.php");
    exit();
}

$imageError = $namError = $typeError = $contenuError = $fileError = "";
$ok = true;

require_once("fonctions.php");
require_once("bd.php");

$sql = $connect->query("SELECT * FROM cours");
$cours = $sql->fetchAll();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["leconName"]) && isset($_POST["leconName"])) {
        $nom_lecon = check_input($_POST["leconName"]);
    } else {
        $namError = "Veuillez renseigner le nom";
        $ok = false;
    }
    if (!empty($_POST["cours"]) && isset($_POST["cours"])) {
        $cours = check_input($_POST["cours"]);
    } else {
        $coursError = "Veuillez sélectionner un cours";
        $ok = false;
    }
    if (!empty($_POST["contenu"]) && isset($_POST["contenu"])) {
        $contenu = $_POST["contenu"];
    } else {
        $contenuError = "Veuillez fournir un contenu";
        $ok = false;
    }
    $date=date("Y-m-d H:i:s");


    // Insertion dans la base de données si tout est valide
    if ($ok) {
        $sql = $connect->prepare("INSERT INTO lecon (titre_lecon, contenu_lecon, id_cours, date_cours) VALUES ( ?, ?, ?, ?)");
        $result = $sql->execute([$nom_lecon, $contenu, $cours, $date]);
        if ($result) {
            header("Location: lecon.php");
            exit();
        } else {
            $fileError = "Erreur lors de l'insertion dans la base de données.";
        }
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
    <script src="ckeditor/ckeditor.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php require_once "navbar.php"; ?>
    <main class="main-content border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">leconistrateurs</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Les lecon</h6>
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
                            <p class="text-xs font-weight-bold mb-0">Type: <?= $_SESSION['type_admin'] == 1 ? 'Super admin' : 'Simple admin' ?></p>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Ajouter des lécons</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-3" enctype="multipart/form-data">
                                    <div class="form-group my-1">
                                        <label for="leconName" class="my-0">Titre de la lécon</label>
                                        <input type="text" class="form-control" id="leconName" name="leconName" value="<?php if (isset($nom_lecon) && !empty($nom_lecon)) {
                                                                                                                            echo $nom_lecon;
                                                                                                                        } ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($namError) && !empty($namError)) {
                                                                                                                echo $namError;
                                                                                                            } ?></p>

                                    </div>
                                    <div class="form-group ">
                                        <label for="leconType" class="my-0">Cours</label>
                                        <select class="form-control" id="cours" name="cours">
                                            <?php foreach ($cours as $cour) { ?>
                                                <option value="<?= $cour["id_cours"] ?>"><?= $cour["nom_cours"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($categorieError) && !empty($categorieError)) {
                                                                                                                echo $categorieError;
                                                                                                            } ?></p>
                                    </div>

                                    <div class="form-group ">
                                        <label for="contenu" class="my-0">contenu du lecon</label>
                                        <textarea type="text" class="form-control ckeditor" id="editor" name="contenu"><?php if (isset($contenu) && !empty($contenu)) {
                                                                                                                            echo $contenu;
                                                                                                                        } ?></textarea>

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