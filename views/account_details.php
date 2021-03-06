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
            <!-- Page title -->
            <h1><?= $page_title ?></h1>
            <!-- Page content -->
            <p><?= $page_content ?></p>
            <table class="table">
                <!-- Table with user information -->
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
                        <td><?= $user_info['birth_date'] ?></td>
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
            <div>
                <!-- Shows the 'Send a message' button if the user you are sending a message to is not the current user himself-->
                <?php if ($user_id != $current_user) { ?> <a href="/DDWT20-Final-Project/send_message/?id=<?= $user_id ?>" type="submit" class="btn btn-primary"><?= $submit_btn ?> </a> <?php } ?>
            </div>

            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>