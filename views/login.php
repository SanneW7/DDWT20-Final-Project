<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <!-- Own CSS -->
        <link rel="stylesheet" href="/DDWT20-Final-Project/css/main.css">

        <!-- Icon -->
        <link rel="shortcut icon" type="image/x-icon" href="css/favicon.ico">

        <!-- Google fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">

        <!-- Page title -->
        <title><?= $page_title ?></title>
    </head>
    <body>
        <!-- Menu -->
        <?= $navigation ?>

        <!-- Content -->
        <div class="container">
            <!-- Breadcrumbs -->
            <div class="pd-15">&nbsp</div>
            <?= $breadcrumbs ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>
                    <!-- Page title -->
                    <h1><?= $page_title ?></h1>
                    <!-- Page subtitle -->
                    <h5><?= $page_subtitle ?></h5>
                    <div class="pd-15">&nbsp;</div>
                    <!-- Login form -->
                    <form action="/DDWT20-Final-Project/login/" method="POST">
                        <div class="form-group">
                            <label for="inputUsername">Gebruikernaam</label>
                            <input type="text" class="form-control col-sm-4" id="inputUsername" placeholder="Gebruikersnaam" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Wachtwoord</label>
                            <input type="password" class="form-control col-sm-4" id="inputPassword" placeholder="*****" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        <div class="form-group">
                            <br>
                            <label for="register">Heb je nog geen account?</label>
                            <br>
                            <a href="/DDWT20-Final-Project/register/" role="button" class="btn btn-secondary">Registreer hier!</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
