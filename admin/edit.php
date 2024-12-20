<?php
  if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if (isset($_POST["adminId"]) && !empty($_POST["adminId"])) {
      $id_admin = check_input($_POST["adminId"]);
      $mod_ok = true;
    }
    if (isset($_POST["adminName"]) && !empty($_POST["adminName"])) {
      $nom_admin = check_input($_POST["adminName"]);
      $mod_ok = true;
    } else {
      $mod_error = 'Veuillez renseigner le nom <span class="fas fa-circle-exclamation"></span>';
      $mod_ok = false;
    }
    if (isset($_POST["adminType"]) && !empty($_POST["adminType"])) {
      $type_admin = check_input($_POST["adminType"]);
      $mod_ok = true;
    } else {
      $mod_error = 'Veuillez renseigner le type <span class="fas fa-circle-exclamation"></span>';
      $mod_ok = false;
    }
    if (isset($_POST["adminEmail"]) && !empty($_POST["adminEmail"])) {
      if (is_email($_POST["adminEmail"])) {
        $email_admin = check_input($_POST["adminEmail"]);
        $mod_ok = true;
      } else {
        $mod_error = 'Entrer un email conforme <span class="fas fa-circle-exclamation"></span>';
        $mod_ok = false;
      }
    } else {
      $mod_error = 'Veuillez renseigner l\'email <span class="fas fa-circle-exclamation"></span>';
      $mod_ok = false;
    }
    if (isset($_POST["password1"]) && !empty($_POST["password1"])) {
      $password1 = check_input($_POST["password1"]);
      $mod_password = true;
      $mod_ok = true;
      if (isset($_POST["password2"]) && !empty($_POST["password2"])) {
        $password2 = check_input($_POST["password2"]);
        $mod_ok = true;
        if ($password1 == $password2) {
          $password = password_hash($password1, PASSWORD_DEFAULT);
          $mod_ok = true;
        } else {
          $mod_error = 'Les mots de passes ne concordent pas <span class="fas fa-circle-exclamation"></span>';
          $mod_ok = false;
        }
      } else {
        $mod_error = 'Veuillez confirmer le mot de passe <span class="fas fa-circle-exclamation"></span>';
        $mod_ok = false;
      }
    }
    if (isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"])) {
      $mod_image = true;
      $mod_ok = true;
      $target_dir = "images/";
      $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
      $uploadOk = true;
      $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

      // Vérification de l'image
      $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
      if ($check !== false) {
        $uploadOk = true;
        $mod_ok = true;
      } else {
        $mod_error = '<b class="text-danger">Le fichier n\'est pas une image.</b>';
        $uploadOk = false;
        $mod_ok = false;
      }

      // Autoriser certains types de fichiers
      if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $mod_error = '<b class="text-danger">Désolé, seuls les formats JPG, JPEG, PNG & GIF sont autorisés <span class="fas fa-circle-exclamation"></span>.</b>';
        $uploadOk = false;
        $mod_ok = false;
      }

      // Enregistrement réussi
      if ($uploadOk) {
        $temp = explode(".", $_FILES["profilePic"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $newfilepath = $target_dir . $newfilename;

        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $newfilepath)) {
          $uploadOk = true;
        }
      }
    }
    if ($mod_ok) {
      if (!$mod_image && !$mod_password) {
        $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=? WHERE id_admin=?");
        $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $id_admin));
        echo json_encode(['success' => true, 'message' => 'Modification réussie']);
        exit();
      } elseif ($mod_image && $mod_password) {
        $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=?, password_admin=?, photo_admin=? WHERE id_admin=?");
        $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $password, $newfilename, $id_admin));
        echo json_encode(['success' => true, 'message' => 'Modification réussie']);
        exit();
      } elseif (!$mod_image && $mod_password) {
        $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=?, password_admin=? WHERE id_admin=?");
        $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $password, $id_admin));
        echo json_encode(['success' => true, 'message' => 'Modification réussie']);
        exit();
      } elseif ($mod_image && !$mod_password) {
        $sql = $connect->prepare("UPDATE administrateurs SET nom_admin=?, email_admin=?, type_admin=?, photo_admin=? WHERE id_admin=?");
        $requete = $sql->execute(array($nom_admin, $email_admin, $type_admin, $newfilename, $id_admin));
        echo json_encode(['success' => true, 'message' => 'Modification réussie']);
        exit();
      }
    }

?>
<div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAdminModalLabel">Modifier un Administrateur</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form for editing admin -->
          <form id="editAdminForm" action="<?php echo check_input($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" id="editAdminId" name="adminId">
            <div class="form-group">
              <p class="text-danger" style="font-size: 12px; height=12px;" ><?php if(isset($mod_error) && !empty($mod_error)){ echo $mod_error; } ?></p>
              <img src="" alt="photo profil" id="adminPhoto" class="img-thumbnail img-fluid rounded-pill mx-auto d-block" style="height: 100px;">
              <label for="profilePic" class="text-xs font-weight-bold mb-0" style="text-align: center; width:100%;">Changer de photo</label>
              <input type="file" name="profilePic" id="profilePic" class="btn btn-primary btn-sm mx-auto d-block">
              </div>
            <div class="form-group">
              <label for="editAdminName" class="text-xs font-weight-bold mb-0">Nom</label>
              <input type="text" class="form-control" id="editAdminName" name="adminName" >
            </div>
            <div class="form-group">
              <label for="editAdminEmail" class="text-xs font-weight-bold mb-0">Email</label>
              <input type="email" class="form-control" id="editAdminEmail" name="adminEmail" >
            </div>
            <div class="form-group">
              <label for="editAdminType" class="text-xs font-weight-bold mb-0">Type</label>
              <select class="form-control" id="editAdminType" name="adminType" required>
                <option value="1">Super Admin</option>
                <option value="2">Simple Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label for="password1" class="text-xs font-weight-bold mb-0">Mot de passe</label>
              <input type="password" class="form-control" id="password1" name="password1" >
            </div>
            <div class="form-group">
              <label for="password2" class="text-xs font-weight-bold mb-0">Confirmer Mot de passe</label>
              <input type="password" class="form-control" id="password2" name="password2" >
            </div>
            <button type="submit" class="btn btn-warning form-control" name="modifier">Modifier <span class=" fas fa-pen-to-square"></span></button>
          </form>
        </div>
      </div>
    </div>
  </div>
