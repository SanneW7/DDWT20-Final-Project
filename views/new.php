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
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php if (isset($error_msg)){echo $error_msg;} ?>
                    <!-- Page title -->
                    <h1><?= $page_title ?></h1>
                    <!-- Page subtitle -->
                    <h5><?= $page_subtitle ?></h5>
                    <!-- Page conent -->
                    <p><?= $page_content ?></p>
                    <!-- New room form -->
                    <form action="<?= $form_action ?>" method="POST">
                        <div class="form-group">
                            <label for="inputPrice" class="col-form-label">Prijs in &euro;</label>
                            <input type="number" min="0" class="form-control col-sm-4" id="inputPrice" name="price" placeholder="200" required value="<?php if (isset($room_info)){echo $room_info['price'];} ?>">
                        </div>

                        <div class="form-group">
                            <label for="inputType cell1" class="col-form-label">Type</label>
                            <select class="form-control col-sm-4" name="type" id="inputType">
                                <option value="Studio">Studio</option>
                                <option value="Appartement">Appartement</option>
                                <option value="Kamer">Kamer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputSize" class="col-form-label">Oppervlakte in m&sup2;</label>
                            <input type="number" min="0" class="form-control col-sm-4" id="inputSize" name="size" placeholder="15" required value="<?php if (isset($room_info)){echo $room_info['size'];} ?>">
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputStreet" class="col-form-label">Straatnaam</label>
                                    <input type="text" class="form-control" id="inputStreet" name="street" placeholder="P.C Hooftstraat" required value="<?php if (isset($room_info)){echo $room_info['streetname'];} ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputHouseNumber" class="col-form-label">Huisnummer</label>
                                    <input type="number" min="0" maxlength="5" class="form-control col-sm-4" id="inputHouseNumber" name="housenumber" placeholder="1" required value="<?php if (isset($room_info)){echo $room_info['house_number'];} ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputCity" class="col-form-label">Stad</label>
                                    <input type="text" class="form-control" id="inputCity" name="city" placeholder="Amsterdam" required value="<?php if (isset($room_info)){echo $room_info['city'];} ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputZipcode" class="col-form-label">Postcode</label>
                                    <input type="text" class="col-sm-4 form-control" id="inputZipcode" placeholder="1234AB" required name="zipcode" pattern="[1-9]{1}[0-9]{3}[A-Za-z]{2}" value="<?php if (isset($room_info)){echo $room_info['zip_code'];} ?>">
                                </div>
                            </div>
                        </div>
                        <!-- Hidden id -->
                        <?php if(isset($room_id)){ ?><input type="hidden" name="id" value="<?php echo $room_id ?>"><?php } ?>
                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary"><?= $submit_btn ?></button>
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