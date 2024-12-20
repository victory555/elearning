<?php
session_start();
if (!isset($_SESSION["nom_admin"]) || empty($_SESSION["nom_admin"]) || !isset($_SESSION["email_admin"]) || empty($_SESSION["email_admin"]) || !isset($_SESSION["type_admin"]) || empty($_SESSION["type_admin"])) {
  header("Location: login.php");
  exit();
}

require_once("fonctions.php");
require_once("bd.php");

$administrateurs = $connect->query("SELECT * FROM administrateurs")->fetchAll(PDO::FETCH_ASSOC);
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
  <link id="pagestyle" href="./elements/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <script src="../js/jquery.min.js"></script>
  <script src="../js/script.js"></script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/all.min.css">
  <script src="../js/all.min.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
        <img src="images/fav32.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">L'éducation à portée de clic.</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-chart-line"></span>
            </div>
            <span class="nav-link-text ms-1">Tableau de bord</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-user-circle"></span>
            </div>
            <span class="nav-link-text ms-1">Mon profil</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-layer-group"></span>
            </div>
            <span class="nav-link-text ms-1">catégories</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-book-open"></span>
            </div>
            <span class="nav-link-text ms-1">Cours</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-hashtag"></span>
            </div>
            <span class="nav-link-text ms-1">tags</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-bars"></span>
            </div>
            <span class="nav-link-text ms-1">contenu</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-blog"></span>
            </div>
            <span class="nav-link-text ms-1">Blog</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-person-chalkboard"></span>
            </div>
            <span class="nav-link-text ms-1">Instructeurs</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-people-group"></span>
            </div>
            <span class="nav-link-text ms-1">Equipe</span>
          </a>
        </li>
        <div style="border-top: 1px solid white;"></div>
        <li class="nav-item">
          <a class="nav-link text-white" href="./pages/dashboard.html">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <span class="fas fa-power-off"></span>
            </div>
            <span class="nav-link-text ms-1">Deconnexion</span>
          </a>
        </li>
        <!-- Add more navigation items here -->
      </ul>
    </div>
  </aside>
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
            <!-- Add more navbar items here -->
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="container ">
          <div class="row mb-3" >
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
                <h6>Liste des administrateurs</h6>
              </div>
              <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0 table-striped table-bordered table-hover" id="myTable">
                    <thead>
                      <tr>
                        <td class="text-sm"><span class="fas fa-list mx-3"></span>Nombre d'administrateurs : <span class="bg-dark p-2 rounded-pill text-white text-b" style="font-weight:bold;"><?php echo count($administrateurs); ?></span></td>
                        <td class="text-sm" colspan="2">Ajouter un administrateur 
                          <a class="btn btn-outline-primary btn-sm mb-0 me-3 mx-3 rounded-pill" href="ajout_admin.php" data-toggle="tooltip" data-placement="bottom" title="Ajouter un admin"><span class="fas fa-plus"></span></a>
                        </td>
                      </tr>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Administrateurs <a href="#" id="sortName"><span class="fas fa-sort mx-3" data-toggle="tooltip" data-placement="bottom" title="Trier les élements"></span></a></th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type
                          <select class="mx-3" id="typeFilter" data-toggle="tooltip" data-placement="bottom" title="Filtrer les élements">
                            <option value="3">Tout</option>
                            <option value="2">Simple</option>
                            <option value="1">Super</option>
                          </select>
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 action">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($administrateurs as $administrateur) : ?>
                        <tr class="affiche" data-type="<?= $administrateur['type_admin'] ?>">
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div>
                                <img src="images/<?= $administrateur['photo_admin'] ?>" class="avatar avatar-sm me-3">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?= $administrateur['nom_admin'] ?></h6>
                                <p class="text-xs text-secondary mb-0"><?= $administrateur['email_admin'] ?></p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?= $administrateur['type_admin'] == 1 ? 'Super Admin' : 'Simple Admin' ?></p>
                          </td>
                          <td class="align-middle cache">
                            <a href="see_admin.php?id_admin=<?php echo $administrateur["id_admin"]; ?>" data-toggle="tooltip" data-placement="bottom" title="Consulter profil"><span class="fas fa-eye"></span></a>
                            <a href="mod_admin.php?id_admin=<?php echo $administrateur["id_admin"]; ?>" class="mx-3" data-toggle="tooltip" data-placement="bottom" title="Modifier administrateur"><span class="fas fa-pen-to-square"></span></a>
                            <a href="del_admin.php?id_admin=<?php echo $administrateur["id_admin"]; ?>" data-toggle="tooltip" data-placement="bottom" title="Supprimer administrateur"><span class="fas fa-trash-can"></span></a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <div id="paginationE" class="mt-3 mx-3">
                  <span class="px-3" id="pagination"></span>
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