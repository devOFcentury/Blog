<?php

require '../checks/check_datas.php';

function checkSignupInput()
{
     global $firstNameError;
     global $lastNameError;
     global $pseudoError;
     global $emailError;
     global $passwordError;
     global $firstname;
     global $lastname;
     global $pseudo;
     global $email;
     global $password;
     global $confirm_password;


     if (empty($_POST['firstname'])) {
          $firstNameError = 'firstname is required';
     } else {
          $firstname = test_input($_POST['firstname']);

          if (!preg_match("/^[a-zA-Zéèëêö ]*$/", $firstname)) {
               $firstNameError = "Only letters and white space allowed";
          }
          
     }

     if (empty($_POST['lastname'])) {
          $lastNameError = 'lastname is required';
     } else {
          $lastname = test_input($_POST['lastname']);

          if (!preg_match("/^[a-zA-Zéèëêö ]*$/", $lastname)) {
               $lastNameError = "Only letters and white space allowed";
          }
     }

     if (empty($_POST['pseudo'])) {
          $pseudoError = 'pseudo is required';
     } else {
          $pseudo = test_input($_POST['pseudo']);
     }

     if (empty($_POST['email'])) {
          $emailError = 'email is required';
     } else {
          $email = test_input($_POST['email']);

          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               $emailError = "Invalid email format";
          }
     }


     if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
          $passwordError = 'password is required';
     } else {
          $password = test_input($_POST['password']);
     }


     if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
          if (strlen($_POST['password']) < 8) {
               $password = '';
               $confirm_password = '';
               $passwordError = "Password less than 8 characters";
          } else {
               if (strcmp($_POST['password'], $_POST['confirm_password']) != 0) {
                    $password = '';
                    $confirm_password = '';
                    $passwordError = 'Passwords do not match';
               }
          }
          

          
     }

     if ($firstNameError !== '' || $lastNameError !== '' || $pseudoError !== '' || $emailError !== '' || $passwordError !== '') {
          return false;
     }

     return true;
}

function signup()
{
     global $pdo;

     global $ft_image_error;
     global $ft_image;
     global $firstname;
     global $lastname;
     global $pseudo;
     global $email;
     global $password;

     $final_image = null;

     $check_image = img_check($_FILES['ft_image']);

     $ft_image = is_array($check_image) ? $check_image : "";
     $ft_image_error = is_string($check_image) && $check_image != '' ? $check_image : "";

     

     if ($ft_image_error !== '') {
          return 0;
     }
    

     if (is_array($ft_image)) {
          $final_image = $ft_image['new_img_name'];
     }

     

     $hashPassword = password_hash($password, PASSWORD_BCRYPT);

     $query = $pdo->prepare("INSERT INTO users(firstname, lastname, pseudo, email, ft_image, password)
     VALUES(:firstname, :lastname, :pseudo, :email, :ft_image, :password)");
     $query->execute(array(
          'firstname' => $firstname,
          'lastname' => $lastname,
          'pseudo' => $pseudo,
          'email' => $email,
          'ft_image' => $final_image,
          'password' => $hashPassword,
     ));

     if (isset($final_image)) {
          move_uploaded_file(
               $ft_image['tmp_img_name'],
               '../images_profile/' .$final_image
          );
     }

     $query_user = $pdo->prepare("SELECT * FROM users WHERE email = :email");
     $query_user->execute(array('email' => $email));

     $data_user = $query_user->fetch();
     
     $_SESSION['id'] = $data_user['id'];
     $_SESSION['firstname'] = $data_user['firstname'];
     $_SESSION['lastname'] = $data_user['lastname'];
     $_SESSION['pseudo'] = $data_user['pseudo'];
     $_SESSION['email'] = $data_user['email'];
     $_SESSION['ft_image'] = $data_user['ft_image'];
     
     header('Location: ../index.php',);

     return 1;
     
}

function signin() {
     global $pdo;

     global $email;
     global $password;
     global $error_credential;

     
     if (empty($_POST['email']) || empty($_POST['password'])) {
          $error_credential = 'Les champs ne doivent pas être vides';
          return 0;
     }
     
     $email = test_input($_POST['email']);
     $password = test_input($_POST['password']);
     
     
     $query = $pdo->prepare("SELECT * FROM users WHERE email= :email");
     $query->execute(array(
          'email' => $email,
     ));

     
     
     $data_user = $query->fetch();
     
     if (empty($data_user) || !password_verify($password, $data_user['password'])) {
          $error_credential = 'Veuillez vérifier votre email ou mot de passe';
          return 0;
     }


     $_SESSION['id'] = $data_user['id'];
     $_SESSION['firstname'] = $data_user['firstname'];
     $_SESSION['lastname'] = $data_user['lastname'];
     $_SESSION['pseudo'] = $data_user['pseudo'];
     $_SESSION['email'] = $data_user['email'];
     $_SESSION['ft_image'] = $data_user['ft_image'];
     
     header('Location: ../index.php',);

     exit;

     return 1;
     
}
