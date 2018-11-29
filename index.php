<?php
require_once 'App.php';
App::perform();
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css" >
    <title>Holy test</title>
    <style>
      body {
        padding-top: 5rem;
      }
      .starter-template {
        padding: 3rem 1.5rem;
        text-align: center;
      }
      .over {
        text-decoration: overline;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="#">Holy test</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Numeric Converter <span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">

      <div class="starter-template">
        <h1>Numeric Converter</h1>
        <p>Converts Arabic digits to Roman numerals and backwards</p>
      </div>
      <form action = "./index.php" method = "GET" >
        <div class="row">
          <div class="form-group col-sm-12 col-md-4">
          <input type="text" class="form-control mr-sm-2"
            placeholder="Type integer here" 
            name="n" 
            autocomplete="off"
            value = "<?php echo App::$input; ?>" />
          </div>
          <div class="col-sm-6 col-md-4 row">
            <div class="form-group col-sm-12 text-center">
            <select class="form-control" name="direction" >
              <option value="1">To Roman</option>
              <option value="2" <?php echo ((App::$d==2)? "selected":"") ?>>To Arabic</option>
            </select>
            </div>
            <div class="form-group col-sm-12 text-center">
            <button class="btn btn-outline-success my-2 my-sm-0" 
                type="submit">convert</button>
            </div>
          </div>
          <div class="form-group col-sm-12 col-md-4">
          <input type="text" class="form-control mr-sm-2"
            placeholder="Result place here" 
            readonly="readonly"
            name="result"
            value = "<?php echo App::$output; ?>" />
          </div>
        </div>
      </form>

    </main><!-- /.container -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./js/jquery-3.3.1.slim.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script>
      var ch = false;
      $('input[name="n"]').change(function(ev){
        ch = true;
      });
      $('select[name="direction"]').change(function(ev){
        var nV = $('input[name="n"]').val();
        var nR = $('input[name="result"]').val();
        if (nR[0] !== '!' && !ch){
          $('input[name="n"]').val(nR);
          $('input[name="result"]').val(nV);
          ch = false;
        }
      });
    </script>
  </body>
</html>