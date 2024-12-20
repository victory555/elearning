<?php
session_start();
if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
  header("Location: login.php");
  exit();
}
require_once("fonctions.php");
require_once("bd.php");

 $mod_name = "";
$mod_ok = false;

if (isset($_GET["id_tag"]) && !empty($_GET["id_tag"])) {
    $id_tag = $_GET["id_tag"];
    $requete = $connect->prepare("SELECT * FROM tags WHERE id_tag=?");
    $requete->execute(array($id_tag));
    $result = $requete->fetch();
}
if($_SESSION["type_admin"]==2){
    $mod_error="Seuls les super Admins sont autorisés à faire des modifs <span class=\" fas fa-exclamation-circle\"></span>";
}else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["tagId"]) && !empty($_POST["tagId"])) {
    $id_tag = check_input($_POST["tagId"]);
    $mod_ok = true;
    }else{
    $mod_ok = false;
    }


    // Validation du nom
    if (isset($_POST["tagName"]) && !empty($_POST["tagName"])) {
        $tagName = check_input($_POST["tagName"]);
    } else {
        $namError = 'Veuillez renseigner le nom';
        $mod_ok = false;
    }

    
    }

    // Mise à jour des informations dans la base de données
    if ($mod_ok) {
            $sql = $connect->prepare("UPDATE tags SET nom_tag=? WHERE id_tag=?");
            $requete = $sql->execute(array($tagName,$id_tag));
            header("Location:tag.php");
        exit();
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
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">Catégories</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Les Catégories</h6>
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
                                <h6>modifier une Catégorie</h6>
                                <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($mod_error) && !empty($mod_error)){ echo $mod_error;} ?></p>

                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-3" enctype="multipart/form-data">
                                    <div class="form-group my-1">
                                        <input type="hidden" class="form-control" id="tagId" name="tagId" value="<?php if(isset($result["id_tag"]) && !empty($result["id_tag"])){ echo $result["id_tag"];}else if(isset($id_tag) && !empty($id_tag)){ echo $id_tag;} ?>">
                                        <label for="adminName" class="my-0">Nom</label>
                                        <input type="text" class="form-control" id="tagName" name="tagName" value="<?php if(isset($result["nom_tag"]) && !empty($result["nom_tag"])){ echo $result["nom_tag"];} else if(isset($tagName) && !empty($tagName)){ echo $tagName;} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if(isset($namError) && !empty($namError)){ echo $namError;} ?></p>

                                    </div> 
                                    <div class="row mt-3">
                                      <div class="col-md-6">
                                        <button type="submit" class="btn btn-warning  form-control  text-white">Modifier <span class="fas fa-pencil mx-3"></span></button>
                                      </div>
                                      <div class="col-md-6">
                                        <a href="index.php" class="btn btn-secondary form-control">Annuler <span class="fas fa-cancel mx-3"></span></a>
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