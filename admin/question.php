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

$questions = $connect->query("SELECT * FROM question lec INNER JOIN quiz c ON c.id_quiz= lec.id_quiz")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["type_admin"] == 1) {
  if (isset($_POST["adminId"]) && !empty($_POST["adminId"])) {
    $id_question = check_input($_POST["adminId"]);
    $ok = true;
  } else {
    $del_error = 'L\'élement à supprimer n\'existe pas <span class="fas fa-circle-exclamation"></span>';
    $ok = false;
  }
  if ($ok) {
    $requete = $connect->prepare("DELETE FROM question WHERE id_question=?");
    $result= $requete->execute(array($id_question));
    if($result){
      $_GET["id_question"] = $id_question;
      header("Location:question.php");

    }

  }
} else {
  $del_error = 'Seule les super-admins sont autorisés à supprimer <span class="fas fa-circle-exclamation"></span>';
  $ok = false;
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
  <script src="ckeditor/ckeditor.js"></script>

</head>

<body class="g-sidenav-show bg-gray-100">
  <?php require_once "navbar.php"; ?>
  <main class="main-content border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;"><span class="fas fa-home mr-1"></span>Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark font-weight-bolder" aria-current="page">question</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Les questions <?php if (isset($id_question)) echo $id_question; ?> </h6>
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
            <li class="nav-item d-flex align-items-center no-print">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="" data-toggle="tooltip" data-placement="bottom" title="Imprimer" id="printTable">
                <span class="fas fa-print"></span>
              </a>
            </li>
            <li class="nav-item d-flex align-items-center no-print">
              <a class="btn btn-outline-primary btn-sm mb-0 me-3" href="" data-toggle="tooltip" data-placement="bottom" title="Exporter en excel" id="exportExcel">
                <span class="fas fa-file-excel"></span>
              </a>
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
                <h6>Liste des question</h6>
              </div>
              <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0 table-striped table-bordered table-hover" id="myTable">
                    <thead>
                      <tr class="no-print">
                        <td class="text-sm"><span class="fas fa-list mx-3"></span>Nombre de question : <span class="bg-dark p-2 rounded-pill text-white text-b" style="font-weight:bold;"><?php echo count($questions); ?></span></td>
                        <td class="text-sm" colspan="2">Ajouter un question
                          <a href="add_question.php" class="btn btn-primary btn-sm mx-3 rounded-pill <?php if ($_SESSION["type_admin"] == 2) echo "disabled"; ?>">
                            <span class="fas fa-plus"></span>
                          </a>
                        </td>
                      </tr>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">question <a href="#" id="sortName"><span class="fas fa-sort mx-3" data-toggle="tooltip" data-placement="bottom" title="Trier les élements"></span></a></th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Les choix 
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 action no-print">réponse </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 action no-print">quiz </th>


                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 action no-print">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($questions as $question) : ?>
                        <tr class="affiche">
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                <div class="row">
                                  <div class="col-2" style="  align-items:center;">
                                    <span class="bg-dark text-white rounded-pill" style="border-right: 5px solid yellow; padding-right: 10px;"><?= $question['id_question'] ?></span>
                                  </div>
                                  <div class="col-10">
                                    <h6 class="mb-0 text-sm text-bold"><?= $question['texte_question'] ?></h6>
                                  </div>
                                </div>
                                
                              </div>
                            </div>
                          </td>
                          <td class="text-center">
                          <a href="see_desc_question.php?id_question=<?= $question['id_question'] ?>" class="btn btn-primary"><span class="fas fa-eye">voir</span></a>


                          <td>
                          <h6 class="mb-0 text-sm text-bold"><?= $question['reponse_question'] ?></h6>


                          </td>
                          <td>
                          <h6 class="mb-0 text-sm text-bold"><?= $question['titre_quiz'] ?></h6>


                          </td>

                          <td class="align-middle no-print">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editAdminModal" data-id="<?php echo $question['id_question']; ?>">
                              <span class="fas fa-eye"></span>
                            </button>
                            <a href="mod_question.php?id_question=<?php echo $question["id_question"]; ?>" class="btn btn-warning btn-sm mx-3 <?php if ($_SESSION["type_admin"] == 2) echo "disabled"; ?>" data-toggle="tooltip" title="Modifier profil profil">
                              <span class="fas fa-pencil-square" style="color: white;"></span>
                            </a>

                            <button type="button" class="btn btn-danger btn-sm <?php if ($_SESSION["type_admin"] == 2) echo "disabled"; ?>" data-toggle="modal" data-target="#deleteAdminModal" data-id="<?php echo $question['id_question']; ?>">
                              <span class="fas fa-trash-can"></span>
                            </button>
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
  <!-- Edit Admin Modal -->
  <div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAdminModalLabel">Consulter un question</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form for editing admin -->
          <form id="editAdminForm" action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" id="editAdminId" name="adminId">
            <div class="form-group">
              <p class="text-danger" style="font-size: 12px; height=12px;"><?php if (isset($mod_error) && !empty($mod_error)) {
                                                                              echo $mod_error;
                                                                            } ?></p>            </div>
            <div class="form-group">
              <label for="editAdminName" class="text-xs font-weight-bold mb-0">Nom</label>
              <input type="text" class="form-control label readonly" id="editAdminName" name="adminName" readonly>
            </div>


            <button type="submit" class="close btn btn-primary form-control p2" name="close" data-dismiss="modal">Fermer <span class=" fas fa-power-off"></span></button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Admin Modal -->
  <div class="modal fade" id="deleteAdminModal" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteAdminModalLabel">Supprimer un question</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Êtes-vous sûr de vouloir supprimer cet question ?</p>
          <form id="deleteAdminForm" action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
            <input type="hidden" id="deleteAdminId" name="adminId">
            <button type="submit" class="btn btn-danger" name="supprimer">Supprimer<span class="fas fa-trash ml-3"></span></button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" value="supprimer">Annuler <span class="fas fa-cancel ml-3"></span></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('printTable').addEventListener('click', function(e) {
      e.preventDefault();

      // Cache les boutons avant d'imprimer
      document.querySelectorAll('.no-print').forEach(function(element) {
        element.style.display = 'none';
      });

      window.print();

      // Affiche les boutons après impression
      document.querySelectorAll('.no-print').forEach(function(element) {
        element.style.display = 'block';
      });
    });

    // Fonction pour exporter en Excel
    document.getElementById('exportExcel').addEventListener('click', function(e) {
      e.preventDefault();

      // Sélectionne le tableau des question (sans les boutons)
      let table = document.getElementById('myTable');
      let wb = XLSX.utils.table_to_book(table, {
        sheet: "question"
      });

      // Télécharge le fichier Excel
      XLSX.writeFile(wb, 'question.xlsx');
    });


    function updateFileName(input) {
      const fileName = input.files[0].name;
      const label = input.nextElementSibling;
      label.textContent = fileName;
    }

    $(document).ready(function() {
      $('#editAdminModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let adminId = button.data('id'); // Extract info from data-* attributes

        let row = button.closest('tr'); // Find the closest row to the button
        let adminName = row.find('h6').text(); // Get the admin name
        let adminEmail = row.find('p').eq(0).text(); // Get the admin email (assure-toi d'utiliser l'indice correct)
        let adminType = row.find('p').eq(1).text(); // Get the admin email (assure-toi d'utiliser l'indice correct)

        let adminPhoto = row.find('img').attr('src');
        let row2 = button.eq('tr');

        // Update the modal fields with the values
        let modal = $(this);
        modal.find('#editAdminId').val(adminId); // Set hidden input
        modal.find('#editAdminName').val(adminName);
        modal.find('#editAdminEmail').val(adminEmail);
        modal.find('#editAdminType').val(adminType);

        modal.find('#adminPhoto').attr('src', adminPhoto); // Update image source
      });

      $('#deleteAdminModal').on('show.bs.modal', function(event) {
        let button = $(event.relatedTarget);
        let adminId = button.data('id');
        let modal = $(this);
        modal.find('#deleteAdminId').val(adminId);
      });
    });
    $(document).ready(function() {
      let sortOrder = 'asc';

      // Tri par nom
      $('#sortName').click(function() {
        let rows = $('#myTable tbody tr.affiche').get();

        rows.sort(function(a, b) {
          let keyA = $(a).find('td:first-child h6').text().toUpperCase();
          let keyB = $(b).find('td:first-child h6').text().toUpperCase();

          if (keyA < keyB) return sortOrder === 'asc' ? -1 : 1;
          if (keyA > keyB) return sortOrder === 'asc' ? 1 : -1;
          return 0;
        });

        $.each(rows, function(index, row) {
          $('#myTable tbody').append(row);
        });

        // Alterne l'ordre de tri
        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
      });

      // Filter by type
      $('#typeFilter').change(function() {
        let type = $(this).val();
        if (type == '3') {
          $('tr.affiche').show();
        } else {
          $('tr.affiche').hide().filter(`[data-type="${type}"]`).show();
        }
      });

      // Search by name
      $('#searchInput').keyup(function() {
        let value = $(this).val().toLowerCase();
        $('#myTable tbody tr.affiche').filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });


    });
  </script>

</body>

</html>