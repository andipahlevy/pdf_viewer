
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>PDF Viewer</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/floating-labels/">

    <!-- Bootstrap core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="floating-labels.css" rel="stylesheet">
  </head>

  <body>
    <form class="form-signin" action="proses.php" method="post" enctype="multipart/form-data">
      <div class="text-center mb-4">
        <img class="mb-4" src="images/download.jpg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">PDF Viewer</h1>
        <p>Choose your file</p>
      </div>

      <div class="form-label-group">
        <input type="file" id="inputEmail" name="file" class="form-control" placeholder="pdf" required autofocus>
        <label for="inputEmail">File pdf</label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" name="upload" type="submit" value="ok">Proses</button>
    </form>
  </body>
</html>
