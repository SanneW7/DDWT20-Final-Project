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

        <!-- Google fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans&display=swap" rel="stylesheet">

        <title><?= $page_title?></title>
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

            <!-- Left column -->
            <div class="col-md-8">
                <!-- Error message -->
                <?php  if (isset($error_msg)){echo $error_msg;} ?>

                <h1><?= $page_title?></h1>
                <h5><?= $page_subtitle?></h5>
                <p><?=  $page_content?></p>
                <div class="card">
                    <div class="card-header">
                        <b>Laatst toegevoegde kamer</b>
                    </div><div class="card-body">
                <table class="table">
                    <tbody>
                    <tr>
                        <th scope="row">Prijs</th>
                        <td>&euro;<?= $latest_room['price'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Type</th>
                        <td><?= $latest_room['type'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Oppervlakte</th>
                        <td><?= $latest_room['size'] ?>m&sup2;</td>
                    </tr>
                    <tr>
                        <th scope="row">Verhuurder</th>
                        <td>
                            <a href="/DDWT20-Final-Project/account_details/?id=<?= $owner_id ?>"><?= $owner_name ?></a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Adres</th>
                        <td><?= $latest_room['streetname']. ' ' .$latest_room['house_number'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Postcode</th>
                        <td><?= $latest_room['zip_code'] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Stad</th>
                        <td><?= $latest_room['city'] ?></td>
                    </tr>

                    </tbody>
                </table>
                        <a href=<?= $newest_room ?> role="button" class="btn btn-primary">Meer informatie</a>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-md-4">
                <!-- Series count -->
                <div class="card">
                    <div class="card-header">
                        Kamers
                    </div>
                    <div class="card-body">
                        <p class="count">Deze site heeft al</p>
                        <h2><?= $amount_rooms ?></h2>
                        <p>kamer(s)</p>
                        <a href="/DDWT20-Final-Project/rooms" class="btn btn-primary">Bekijk kamers</a>
                    </div>
                </div>
                <br/>
                <!-- Users count -->
                <div class="card">
                    <div class="card-header">
                        Gebruikers
                    </div>
                    <div class="card-body">
                        <p class="count">Deze site heeft al</p>
                        <h2><?= $amount_users ?></h2>
                        <p>gebruiker(s)</p>
                        <a href="/DDWT20-Final-Project/register" class="btn btn-primary">Registreer!</a>
                    </div>
                </div>
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