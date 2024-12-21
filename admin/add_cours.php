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

$imageError = $namError = $typeError = $descriptionError = $fileError = "";
$ok = true;

require_once("fonctions.php");
require_once("bd.php");

$sql = $connect->query("SELECT * FROM categories");
$categories = $sql->fetchAll();
$sql2 = $connect->query("SELECT * FROM instructeurs");
$instructeurs = $sql2->fetchAll();
$sql3 = $connect->query("SELECT * FROM tags");
$tags = $sql3->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["coursName"]) && isset($_POST["coursName"])) {
        $nom_cours = check_input($_POST["coursName"]);
    } else {
        $namError = "Veuillez renseigner le nom";
        $ok = false;
    }
    if (!empty($_POST["categorie"]) && isset($_POST["categorie"])) {
        $categorie = check_input($_POST["categorie"]);
    } else {
        $categorieError = "Veuillez sélectionner une catégorie";
        $ok = false;
    }

    if (!empty($_POST["instructeur"]) && isset($_POST["instructeur"])) {
        $instructeur = check_input($_POST["instructeur"]);
    } else {
        $instructeurError = "Veuillez sélectionner un instructeur";
        $ok = false;
    }
    if (!empty($_POST["description"]) && isset($_POST["description"])) {
        $description = $_POST["description"];
    } else {
        $descriptionError = "Veuillez fournir une description";
        $ok = false;
    }


    // Insertion dans la base de données si tout est valide
    if ($ok) {
        $sql = $connect->prepare("INSERT INTO cours (nom_cours, description_cours, id_categorie, id_ins) VALUES ( ?, ?, ?, ?)");
        $result = $sql->execute([$nom_cours, $description, $categorie, $instructeur]);
        if ($result) {
            $cours_id = $connect->lastInsertId(); // Récupère l'ID du cours inséré

            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tag_id) {
                    $sql_tag = $connect->prepare("INSERT INTO cours_tags (id_cours, id_tag) VALUES (?, ?)");
                    $sql_tag->execute([$cours_id, $tag_id]);
                }
            }
            header("Location: cours.php");
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
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">coursistrateurs</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Les cours</h6>
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
                                <h6>Ajouter des cours</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-3" enctype="multipart/form-data">
                                    <div class="form-group my-1">
                                        <label for="coursName" class="my-0">Nom</label>
                                        <input type="text" class="form-control" id="coursName" name="coursName" value="<?php if (isset($nom_cours) && !empty($nom_cours)) {
                                                                                                                            echo $nom_cours;
                                                                                                                        } ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($namError) && !empty($namError)) {
                                                                                                                echo $namError;
                                                                                                            } ?></p>

                                    </div>
                                    <div class="form-group ">
                                        <label for="coursType" class="my-0">Catégorie</label>
                                        <select class="form-control" id="categorie" name="categorie">
                                            <?php foreach ($categories as $categorie) { ?>
                                                <option value="<?= $categorie["id_categorie"] ?>"><?= $categorie["nom_categorie"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($categorieError) && !empty($categorieError)) {
                                                                                                                echo $categorieError;
                                                                                                            } ?></p>
                                    </div>
                                    <div class="form-group ">
                                        <label for="coursType" class="my-0">Instructeurs</label>
                                        <select class="form-control" id="instructeur" name="instructeur">
                                            <?php foreach ($instructeurs as $instructeur) { ?>
                                                <option value="<?= $instructeur["id_ins"] ?>"><?= $instructeur["nom_ins"] . " | " . $instructeur["email_ins"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($instructeurError) && !empty($instructeurError)) {
                                                                                                                echo $instructeureError;
                                                                                                            } ?></p>
                                    </div>

                                    <div class="form-group ">
                                        <label for="description" class="my-0">description du cours</label>
                                        <textarea type="text" class="form-control" id="description" name="description"><?php if (isset($description) && !empty($description)) {
                                                                                                                            echo $description;
                                                                                                                        } ?></textarea>

                                    </div>
                                    <div class="form-group">
                                        <label for="tags" class="my-0">Tags</label>
                                        <div id="tags">
                                            <?php foreach ($tags as $tag): ?>
                                                <label class="mr-2">
                                                    <input type="checkbox" name="tags[]" value="<?= $tag['id_tag'] ?>"> <?= htmlspecialchars($tag['nom_tag']) ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
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