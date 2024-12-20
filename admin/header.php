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
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Round">
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
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="d-flex flex-column justify-content-center">
                <h1 class="h1">Administrateurs</h1>
                <a href="#ajouterAdminModal" class="btn btn-primary" data-bs-toggle="modal"><span class="fas fa-plus-circle"></span> Ajouter un administrateur</a>
              </div>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center">
              <div class="btn-group">
                <button class="btn btn-secondary" id="prevPageBtn" onclick="prevPage()">Précédent</button>
                <button class="btn btn-secondary" id="nextPageBtn" onclick="nextPage()">Suivant</button>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
              <thead>
                <tr>
                  <th scope="col">Nom complet</th>
                  <th scope="col">Email</th>
                  <th scope="col">Telephone</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($administrateurs as $admin) {
                  echo "<tr>";
                  echo "<td>{$admin['nom_complet']}</td>";
                  echo "<td>{$admin['email']}</td>";
                  echo "<td>{$admin['telephone']}</td>";
                  echo "<td>
                    <a href='#modifierAdminModal' data-bs-toggle='modal' data-id='{$admin['id']}' class='edit'><span class='fas fa-edit'></span></a>
                    <a href='#supprimerAdminModal' data-bs-toggle='modal' data-id='{$admin['id']}' class='delete'><span class='fas fa-trash-alt'></span></a>
                    </td>";
                  echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modale Ajouter -->
  <div class="modal fade" id="ajouterAdminModal" tabindex="-1" aria-labelledby="ajouterAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ajouterAdminModalLabel">Ajouter un administrateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="ajouter_admin.php" method="post">
            <div class="mb-3">
              <label for="nom_complet" class="form-label">Nom complet</label>
              <input type="text" class="form-control" id="nom_complet" name="nom_complet" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="telephone" class="form-label">Telephone</label>
              <input type="text" class="form-control" id="telephone" name="telephone" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modale Modifier -->
  <div class="modal fade" id="modifierAdminModal" tabindex="-1" aria-labelledby="modifierAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modifierAdminModalLabel">Modifier un administrateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="modifier_admin.php" method="post">
            <input type="hidden" id="id" name="id">
            <div class="mb-3">
              <label for="nom_complet" class="form-label">Nom complet</label>
              <input type="text" class="form-control" id="nom_complet" name="nom_complet" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="telephone" class="form-label">Telephone</label>
              <input type="text" class="form-control" id="telephone" name="telephone" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modale Supprimer -->
  <div class="modal fade" id="supprimerAdminModal" tabindex="-1" aria-labelledby="supprimerAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="supprimerAdminModalLabel">Supprimer un administrateur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir supprimer cet administrateur ?</p>
          <form action="supprimer_admin.php" method="post">
            <input type="hidden" id="id" name="id">
            <button type="submit" class="btn btn-danger">Supprimer</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#modifierAdminModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        var modal = $(this);
        modal.find('#id').val(id);
        // Add code to populate the form fields with the admin data using AJAX or other method
      });

      $('#supprimerAdminModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        var modal = $(this);
        modal.find('#id').val(id);
      });
    });
  </script>
</body>

</html>
