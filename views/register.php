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
                    <!-- Page title -->
                    <h1><?= $page_title ?></h1>
                    <!-- Page subtitle -->
                    <h5><?= $page_subtitle ?></h5>
                    <div class="pd-15">&nbsp;</div>
                    <!-- Register form -->
                    <form action=<?= $form_action ?> method="POST">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputUsername">Gebruikersnaam</label>
                                    <input type="text" class="form-control col-sm-8" id="inputUsername" placeholder="j.jansen" name="username"  required value="<?php if (isset($user_info)){echo $user_info['username'];} ?>">
                                </div>
                            </div>
                            <div class="col">
                                <?php if (!isset($user_info)){ ?>
                                    <div class="form-group">
                                        <label for="inputPassword">Wachtwoord</label>
                                        <input type="password" class="form-control col-sm-8" id="inputPassword" placeholder="******" name="password"  required value="">
                                    </div>
                                <?php }  ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputFullname">Volledige naam</label>
                                    <input type="text" class="form-control col-sm-8" id="inputFullname" placeholder="Jan Jansen" name="full_name" required value="<?php if (isset($user_info)){echo $user_info['full_name'];} ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputEmail">Email</label>
                                    <input type="email" class="form-control col-sm-8" id="inputEmail" placeholder="j.jansen@email.com" name="email" required value="<?php if (isset($user_info)){echo $user_info['email'];} ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputPhonenumber">Telefoonnummer</label>
                                    <input type="tel" class="form-control col-sm-8" id="inputPhonenumber" placeholder="0612345678" name="phonenumber" required pattern="^(06)[0-9]{8}$" value="<?php if (isset($user_info)){echo $user_info['phonenumber'];} ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputBirthdate">Geboortedatum</label>
                                    <input type="date" class="form-control col-sm-8" id="inputBirthdate" placeholder="DD-MM-YYYY" name="birth_date" required value="<?php if (isset($user_info)){echo $user_info['birth_date'];} ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputLanguage">Taal</label>
                                    <input type="text" class="form-control col-sm-8" id="inputLanguage" placeholder="Nederlands" name="language" required value="<?php if (isset($user_info)){echo $user_info['language'];} ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputStudyProfession">Studie of Beroep</label>
                                    <input type="text" class="form-control col-sm-8" id="inputStudyProfession" placeholder="Informatiekunde" name="occupation" required value="<?php if (isset($user_info)){echo $user_info['occupation'];} ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="inputBiography">Biografie</label>
                                    <textarea maxlength="255" class="form-control col-lg-10" id="inputBiography" placeholder="Bio" name="biography" required><?php if (isset($user_info)){echo $user_info['biography'];} ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <!-- Radiobuttons hidden when user is in edit page and shown when in register page -->
                                <?php if (!isset($user_info)){ ?>
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
                            </div>
                        </div>
                        <?php } ?>
                        <!-- Hidden id -->
                        <?php if(isset($user_id)){ ?><input type="hidden" name="id" value="<?php echo $user_id ?>"><?php } ?>
                        <div class="row">
                            <div class="col-sm-2">
                                <!-- Edit or register button -->
                                <button type="submit" class="btn btn-primary"><?= $button_name ?></button>
                            </div>

                    </form>
                    <?php if(isset($user_info)){ ?>
                    <!-- Delete button shown when user is on edit page -->
                    <form action="/DDWT20-Final-Project/delete_account/" method="POST">
                        <div class="form-group">
                            <!-- Hidden id -->
                            <input type="hidden" value="<?= $user_info['id'] ?>" name="id">
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Weet je zeker dat je je account wilt verwijderen?')">Account verwijderen</button>
                            </div>
                        </div>
                    </form>
                </div>
                    <?php } ?>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
