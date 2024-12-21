<?php
session_start();
if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
    header("Location: login.php");
    exit();
}
$mod_error = $del_error = "";
$mod_image = $mod_ok = $mod_password = $ok = false;

require_once("fonctions.php");
require_once("bd.php");
if(isset($_GET['id_lecon']) && !empty($_GET['id_lecon'])){
    $id_lecon=$_GET['id_lecon'];
    $requete=$connect->prepare("SELECT * FROM lecon WHERE id_lecon=?");
    $requete->execute(array($id_lecon));
    $result=$requete->fetch();

}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="images/EDU header.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edutec </title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="./elements/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./elements/css/nucleo-svg.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="./elements/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <script src="../js/xlsx.full.min.js"></script>
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
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="index.php"><span class="fas fa-home mr-1"></span>Administrateurs</a></li>
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">contenu</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">contenu d'une lecon</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                        </div>
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="setting.php" data-toggle="tooltip" data-placement="bottom" title="Parametres"> <span class="fas fa-gear"></span></a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="deconnexion.php" data-toggle="tooltip" data-placement="bottom" title="Se deconnecter"> <span class="fas fa-power-off"></span></a>
                        </li>


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
                    <div class="col-12" style="height: auto;">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Voir la contenu</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <div class="container">
                                        <div class="row">

                                                <div class="mt-5"> 
                                                    <p class="mb-2 text-sm text-bold"><span class="titre"> contenu:</span>  <?php if(isset($result['contenu_lecon']) && !empty($result['contenu_lecon'])){ echo $result["contenu_lecon"];}?></p>
                                                    <a class="btn btn-dark mt-2 btn-sm" href="lecon.php"> <span class="fas fa-arrow-left mr-2"></span> Retour</a>
                                                </div>
                                               

                                            </div>
                                        </div>
                                    </div>
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