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
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>
                    <!-- Page title -->
                    <h1><?= $page_title ?></h1>
                    <!-- Page subtitle -->
                    <h5><?= $page_subtitle ?></h5>
                    <!-- Page content -->
                    <p><?= $page_content ?></p>
                    <!-- Room information -->
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Prijs</th>
                                <td>&euro;<?= $room_info['price'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Type</th>
                                <td><?= $room_info['type'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Oppervlakte</th>
                                <td><?= $room_info['size'] ?>m&sup2;</td>
                            </tr>
                            <tr>
                                <th scope="row">Verhuurder</th>
                                <td>
                                    <!-- Redirects to the owner information -->
                                    <a href="/DDWT20-Final-Project/account_details/?id=<?= $owner_id ?>"><?= $owner_name ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Adres</th>
                                <td><?= $room_info['streetname']. ' ' .$room_info['house_number'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Postcode</th>
                                <td><?= $room_info['zip_code'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Stad</th>
                                <td><?= $room_info['city'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Shows edit and remove button if you are the owner-->
                    <?php if ($display_buttons_owner) { ?>
                    <div class="row">
                        <div class="col-sm-2">
                            <a href="/DDWT20-Final-Project/edit/?id=<?= $room_info['id'] ?>" role="button" class="btn btn-warning">Aanpassen</a>
                        </div>
                        <div class="col-sm-2">
                            <form action="/DDWT20-Final-Project/delete/" method="POST">
                                <input type="hidden" value="<?= $room_info['id'] ?>" name="id">
                                <button type="submit" onclick="return confirm('Weet je zeker dat je de kamer wilt verwijderen?')" class="btn btn-danger">Verwijderen</button>
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <!-- Number of opt ins -->
                        <div class="card-header">
                            Deze kamer heeft <?= $number_opt_ins ?> inschrijving(en)
                        </div>
                        <?php if ($display_buttons_owner) { ?>
                            <div>
                                <!-- Shows to owner the users who have opted in -->
                                <?php if(isset($opt_in_content)){
                                    echo $opt_in_content;
                                }?>
                            </div>
                            <?php } ?>
                    </div>
                    <br/>
                    <?php if ($display_buttons_tenant) { ?>
                        <!-- Shows opt in button for tenants -->
                        <div class="row">
                            <div class="col-sm-2">
                                <form action="/DDWT20-Final-Project/opt-in/" method="POST">
                                    <!-- Hidden id -->
                                    <input type="hidden" type="number" value="<?= $room_info['id'] ?>" name="id">
                                    <?= $button_opt_in ?>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
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
