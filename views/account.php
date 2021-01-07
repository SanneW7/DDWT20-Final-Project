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
                    <h1><?= $page_title.$user ?></h1>
                    <h5><?= $page_subtitle ?></h5>
                    <p><?= $page_content ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <!-- Data of the user in a card -->
                        <div class="card-header">
                            Je bent ingelogd in Kamernet2
                        </div>
                        <div class="card-body">
                            <p>De gegevens van <?= $username ?></p>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th scope="row">Gebruikersnaam</th>
                                    <td><?= $user_info['username'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Naam</th>
                                    <td><?= $user_info['full_name'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td><?= $user_info['email'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Telefoonnummer</th>
                                    <td><?= $user_info['phonenumber'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Geboortedatum</th>
                                    <td><?= $date_time = date('d-m-Y', strtotime($user_info['birth_date'])) ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Taal</th>
                                    <td><?= $user_info['language'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Studie/Beroep</th>
                                    <td><?= $user_info['occupation'] ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Biografie</th>
                                    <td><?= $user_info['biography'] ?></td>
                                </tr>

                                </tbody>
                            </table>
                            <!-- Buttons to change your data and to log out -->
                            <a href="/DDWT20-Final-Project/edit_account/" class="btn btn-warning">Bewerk je gegevens</a>
                            <a href="/DDWT20-Final-Project/logout/" class="btn btn-primary">Uitloggen</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <!-- Shows either your rooms or your opt ins -->
                            <?= $card_header ?>
                        </div>
                        <div>
                            <?php if(isset($room_content)){
                                echo $room_content;
                            } ?>
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