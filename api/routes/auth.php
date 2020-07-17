<?php

namespace MyApp\Routes;

require_once __DIR__ . '/../config.php';
require_once DB_DIR . '/users.php';
require_once LIB_DIR . '/auth.php';

use MyApp\DataBase\Users as DB_Users;
use MyApp\Auth as LIB_Auth;
use \Exception;

class Auth
{
  /**
   * Login handler
   */
  public static function login()
  {
    $email = trim(filter_input(INPUT_POST, 'email'));
    $password = trim(filter_input(INPUT_POST, 'password'));

    try {
      $userData = DB_Users\get_user_by_email($email);

      if (empty($userData) || !LIB_Auth\verify_password($password, $userData['password'])) {
        self::send_validation_error(
          'E-mail or password is different.',
          [
            'email' => $email,
            'password' => $password,
          ]
        );
        exit();
      }

      $data = [
        'id' => $userData['id'],
        'username' => $userData['name'],
      ];

      // create JWT token
      $token = LIB_Auth\create_jwt_token([
        'id' => $data['id'],
      ]);

      return_json([
        'data'  => $data,
        'token' => $token,
      ]);
    } catch (Exception $e) {
      self::send_error($e);
    }
  }


  /**
   * Sign up handler
   */
  public static function signup()
  {
    $username = trim(filter_input(INPUT_POST, 'username'));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $email = trim(filter_input(INPUT_POST, 'email'));

    // Validation
    $errors = self::validate_signup_data($username, $email, $password);
    if (!empty($errors)) {
      self::send_validation_error($errors, [
        'username' => $username,
        'email'    => $email,
        'password' => $password,
      ]);
      exit();
    }

    try {
      $data = DB_Users\create_user($username, $email, $password);

      // create JWT token
      $token = LIB_Auth\create_jwt_token([
        'id' => $data['id'],
      ]);

      return_json([
        'data'  => $data,
        'token' => $token,
      ]);
    } catch (Exception $e) {
      self::send_error($e);
    }
  }


  /**
   * Validate signup form data
   */
  private static function validate_signup_data($username, $email, $password)
  {
    $errors = [];

    if (empty($username)) {
      $errors['username'] = 'Please enter user name.';
    } else if (!preg_match('/^([\w\-]+)$/', $username)) {
      $errors['username'] = 'User name can include alphabets, `-` and `_`.';
    } else {
      // check user name already used.
      try {
        $userData = DB_Users\get_user_by_name($username);
        if (!empty($userData)) {
          $errors['username'] = 'Thie Username is already registered.';
        }
      } catch (Exception $e) {
        $errors['error'] = $e->getMessage();
      }
    }

    if (empty($password)) {
      $errors['password'] = 'Please enter a password';
    } else if (strlen($password) !== mb_strlen($password)) {
      $errors['password'] = 'Enter the password in single-byte characters.';
    } else if (preg_match("/\s+/", $password)) {
      $errors['password'] = 'Password cannot contain spaces.';
    } else if (strlen($password) < 6) {
      $errors['password'] = 'Please enter a password with at least 6 characters.';
    }

    if (empty($email)) {
      $errors['email'] = 'Please enter your E-mail';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'The E-mail is invalid';
    } else {
      // check email already used.
      try {
        $userData = DB_Users\get_user_by_email($email);
        if (!empty($userData)) {
          $errors['email'] = 'Thie E-mail is already registered.';
        }
      } catch (Exception $e) {
        $errors['error'] = $e->getMessage();
      }
    }
    return $errors;
  }

  // Send validation error.
  private static function send_validation_error($errors, $data = null)
  {
    return_json([
      'message' => 'validation error',
      'errors' => $errors,
      'data' => $data,
    ], false, 200);
  }

  // return Error
  private static function send_error($e)
  {
    $status = 500;
    $error = $e->getMessage();
    return_json(['message' => $error], false, $status);
  }
}
