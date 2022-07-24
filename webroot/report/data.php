<?php
// this is bad code, it's not entirely insecure (no unsanitised user input)
// but it's not the best way to do it either, it's just a quick and dirty way to
// get the data into a page.
require_once('../lib/inc/config.php');
require_once('../lib/inc/db.php');

session_start();
$config = get_config();

// if no password is configured, just quit.
if(!isset($config['report_pw']) || empty($config['report_pw'])) {
  header('HTTP/1.1 400 Bad Request');
  die('Invalid request method.');
}

header('Cache-Control: no-cache, no-store, must-revalidate');

$content = '';
if((!isset($_SESSION['user']) || $_SESSION['user'] !== 'report') && !isset($_POST['password'])) {
  $content = file_get_contents('./login.html');
} else {
  if(isset($_POST['password'])) {
    if($_POST['password'] === $config['report_pw']) {
      $_SESSION['user'] = 'report';
    } else {
      $content = file_get_contents('./login.html');
    }
  } else {
    db_connect_mysqli($config);
    $ratings = db_get_rating();
    // var_dump($ratings);
    ob_start();
    require_once('./report.php');
    $content = ob_get_clean();
  }
  
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h1>Report</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <?php
          echo $content;
          ?>
        </div>
      </div>
    </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>