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

            <form action="/DDWT20_finalproject/register/" method="POST">
                <div class="form-group">
                    <label for="inputUsername">Username</label>
                    <input type="text" class="form-control" id="inputUsername" placeholder="j.jansen" name="username" required>
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="******" name="password" required>
                </div>
                <div class="form-group">
                    <label for="inputFullname">Full name</label>
                    <input type="text" class="form-control" id="inputFullname" placeholder="Jan Jansen" name="fullname" required>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="j.jansen@email.com" name="email" required>
                </div>
                <div class="form-group">
                    <label for="inputPhonenumber">Phone number</label>
                    <input type="tel" class="form-control" id="inputPhonenumber" placeholder="0612345678" name="phonenumber" required>
                </div>
                <div class="form-group">
                    <label for="inputBirthdate">Birth date</label>
                    <input type="date" class="form-control" id="inputBirthdate" placeholder="YYYY-MM-DD" name="birthdate" required>
                </div>
                <div class="form-group">
                    <label for="inputLanguage">Language</label>
                    <input type="text" class="form-control" id="inputLanguage" placeholder="Dutch" name="language" required>
                </div>
                <div class="form-group">
                    <label for="inputStudyProfession">Study/Profession</label>
                    <input type="text" class="form-control" id="inputStudyProfession" placeholder="Information Science" name="studyprofession" required>
                </div>
                <div class="form-group">
                    <label for="inputBiography">Biography</label>
                    <input type="text" class="form-control" id="inputBiography" placeholder="Bio" name="biography" required>
                </div>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="tenant" value="tenant">
                        <label class="form-check-label" for="tenant">I want to rent a room</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="owner" value="owner">
                        <label class="form-check-label" for="owner">I want to rent out a room</label>
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
