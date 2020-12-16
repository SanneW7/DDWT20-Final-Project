<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Own CSS -->
    <!--<link rel="stylesheet" href="/DDWT20/week2/css/main.css">-->

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

        <!-- Left column -->
        <div class="col-md-12">
            <!-- Error message -->
            <?php if (isset($error_msg)){echo $error_msg;} ?>

            <h1><?= $page_title ?></h1>
            <h5><?= $page_subtitle ?></h5>

            <div class="pd-15">&nbsp;</div>

            <form action="/DDWT20-Final-Project/register/" method="POST">
                <div class="form-group">
                    <label for="inputUsername">Gebruikersnaam</label>
                    <input type="text" class="form-control" id="inputUsername" placeholder="j.jansen" name="username">
                </div>
                <div class="form-group">
                    <label for="inputPassword">Wachtwoord</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="******" name="password">
                </div>
                <div class="form-group">
                    <label for="inputFullname">Volledige naam</label>
                    <input type="text" class="form-control" id="inputFullname" placeholder="Jan Jansen" name="full_name">
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="j.jansen@email.com" name="email">
                </div>
                <div class="form-group">
                    <label for="inputPhonenumber">Telefoonnummer</label>
                    <input type="tel" class="form-control" id="inputPhonenumber" placeholder="0612345678" name="phonenumber">
                </div>
                <div class="form-group">
                    <label for="inputBirthdate">Geboortedatum</label>
                    <input type="text" class="form-control" id="inputBirthdate" placeholder="YYYY-MM-DD" name="birth_date">
                </div>
                <div class="form-group">
                    <label for="inputLanguage">Taal</label>
                    <input type="text" class="form-control" id="inputLanguage" placeholder="Dutch" name="language">
                </div>
                <div class="form-group">
                    <label for="inputStudyProfession">Studie of Beroep</label>
                    <input type="text" class="form-control" id="inputStudyProfession" placeholder="Information Science" name="occupation">
                </div>
                <div class="form-group">
                    <label for="inputBiography">Biografie</label>
                    <textarea type="text" class="form-control" id="inputBiography" placeholder="Bio" name="biography"></textarea>
                </div>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="tenant" value="0" checked="checked">
                        <label class="form-check-label" for="tenant">Ik wil een kamer huren</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="owner" value="1">
                        <label class="form-check-label" for="owner">Ik wil een kamer verhuren</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Register now</button>
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
