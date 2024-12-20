
<?php
session_start();

require_once("fonctions.php");
require_once("bd.php");

if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || 
    !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || 
    !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
    header("Location: login.php");
    exit();
}
$sql = $connect->query("SELECT * FROM categories");
$categories = $sql->fetchAll();

if (isset($_GET["id_livre"]) && !empty($_GET["id_livre"])) {
    $id_livre = check_input($_GET["id_livre"]);
    $sql = $connect->prepare("SELECT * FROM livres li INNER JOIN categories cat ON cat.id_categorie = li.id_categorie WHERE id_livre = ?");
    $sql->execute([$id_livre]);
    $livre = $sql->fetch();
}

$imageError = $namError = $typeError = $descriptionError = $fileError = "";
$ok = true;
$mod_pic = $mod_file = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["id_livre"])) {
        $id_livre = check_input($_POST["id_livre"]);
    } else {
        $namError = "element à supprimer non séléctionné";
        $ok = false;
    }

    if (!empty($_POST["livreName"])) {
        $nom_livre = check_input($_POST["livreName"]);
    } else {
        $namError = "Veuillez renseigner le nom";
        $ok = false;
    }

    if (!empty($_POST["categorie"])) {
        $categorie = check_input($_POST["categorie"]);
    } else {
        $categorieError = "Veuillez sélectionner une catégorie";
        $ok = false;
    }

    if (!empty($_POST["description"])) {
        $description = check_input($_POST["description"]);
    } else {
        $descriptionError = "Veuillez fournir une description";
        $ok = false;
    }

    if (!empty($_FILES["profilePic"]["name"])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $mod_pic = true;

        // Vérification du type de fichier
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array(strtolower($imageFileType), $allowed_types)) {
            $imageError = "Seuls les formats JPG, JPEG, PNG et GIF sont acceptés.";
            $ok = false;
        } else {
            $newfilename = round(microtime(true)) . '.' . $imageFileType;
            $newfilepath = $target_dir . $newfilename;

            if (!move_uploaded_file($_FILES["profilePic"]["tmp_name"], $newfilepath)) {
                $imageError = "Erreur lors de l'upload de l'image.";
                $ok = false;
            }
        }
    }

    if (!empty($_FILES["file1"]["name"])) {
        $mod_file = true;
        $target_file = "livres/" . basename($_FILES["file1"]["name"]);
        if (!move_uploaded_file($_FILES["file1"]["tmp_name"], $target_file)) {
            $fileError = "Erreur lors de l'upload du fichier.";
            $ok = false;
        }
    }

    if ($ok) {
        $query = "UPDATE livres SET nom_livre = ?, description_livre = ?, id_categorie = ?";
        $params = [$nom_livre, $description, $categorie];

        if ($mod_pic) {
            $query .= ", photo_livre = ?";
            $params[] = $newfilename;
        }

        if ($mod_file) {
            $query .= ", fichier_livre = ?";
            $params[] = basename($_FILES["file1"]["name"]);
        }

        $query .= " WHERE id_livre = ?";
        $params[] = $id_livre;

        $sql = $connect->prepare($query);
        $result = $sql->execute($params);

        if ($result) {
            header("Location: librairie.php");
            exit();
        } else {
            $fileError = "Erreur lors de la mise à jour dans la base de données.";
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
</head>

<body class="g-sidenav-show bg-gray-100">
    <?php require_once "navbar.php"; ?>
    <main class="main-content border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">livreistrateurs</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Modifier un livre</h6>
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
                                <h6>Ajouter des livres</h6>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <form action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-3" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="hidden" value="<?php if(isset($id_livre) && !empty($id_livre)) echo $id_livre?>" id="id_livre" name="id_livre">
                                    </div>
                                    <div class="form-group my-1">
                                        <label for="livreName" class="my-0">Nom</label>
                                        <input type="text" class="form-control" id="livreName" name="livreName" value="<?php if (isset($livre["nom_livre"]) && !empty($livre["nom_livre"])) { echo $livre["nom_livre"];} ?>">
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($namError) && !empty($namError)) {
                                                                                                                echo $namError;
                                                                                                            } ?></p>

                                    </div>
                                    <div class="form-group ">
                                        <label for="livreType" class="my-0">Catégorie</label>
                                        <select class="form-control" id="categorie" name="categorie">
                                            <?php foreach ($categories as $categorie) { ?>
                                                <option value="<?= $categorie["id_categorie"] ?>" <?php  if($categorie["id_categorie"]==$livre["id_livre"]) echo "selected"; ?>><?= $categorie["nom_categorie"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="text-danger" style="font-size: 10px; font-weight: bold;"><?php if (isset($categorieError) && !empty($categorieError)) {
                                                                                                                echo $categorieError;
                                                                                                            } ?></p>
                                    </div>

                                    <div class="form-group">
                                        <label class="btn btn-success btn-sm" for="my-file-selector1">
                                            <input id="my-file-selector1" name="file1" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                            <span class=" fas fa-upload "></span>télécharger livre
                                        </label>
                                        <span class='label label-success text-danger' id="upload-file-info"><?php if(isset($livre["fichier_livre"]) && !empty($livre["fichier_livre"])){ echo $livre["fichier_livre"]; }?></span>
                                        <?php if (isset($file_error)) {
                                            echo $file_error;
                                        } ?>
                                    </div>
                                    <div class="form-group ">
                                        <label for="description" class="my-0">description du livre</label>
                                        <textarea type="text" class="form-control" id="description" name="description"> <?php if(isset($livre["description_livre"]) && !empty($livre["description_livre"])){echo $livre["description_livre"];} else if (isset($description) && !empty($description)) {
                                                                                                                                echo $description;
                                                                                                                            } ?>
                                        </textarea>

                                    </div>
                                    <div class="custom-file form-group ">
                                        <label for="profilePic" class="my-0">Photo de couverture du livre</label>
                                        <input type="file" class="custom-file-input form-control" id="profilePic" name="profilePic" aria-describedby="inputGroupFileAddon01" onchange="updateFileName(this)">
                                        <label class="custom-file-label" for="profilePic">choisir une photo</label>
                                        <span class='label label-success text-danger' id="upload-file-info"><?php if(isset($livre["photo_livre"]) && !empty($livre["photo_livre"])){ echo $livre["photo_livre"]; }?></span>

                                        <p class="text-danger" style="font-size: 10px; font-weight: bold"><?php if (isset($imageError) && !empty($imageError)) {
                                                                                                                echo $imageError;
                                                                                                            } ?></p>

                                        <p><?php if (isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]))  echo $_FILES["profilePic"]["name"]; ?>
                                        </p>

                                    </div>
                                    <button type="submit" class="btn btn-warning  form-control mt-3">Modifier <span class="fas fa-pencil mx-3"></span></button>
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