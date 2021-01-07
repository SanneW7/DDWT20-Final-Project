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
                <div class="col-md-8">
                    <!-- Error message -->
                    <?php  if (isset($error_msg)){echo $error_msg;} ?>
                    <!-- Page title -->
                    <h1><?= $page_title?></h1>
                    <!-- Page subtitle -->
                    <h5><?= $page_subtitle?></h5>
                    <!-- Page content -->
                    <p><?=  $page_content?></p>
                    <!-- Shows the message history -->
                    <?php  if(!empty($message_history)){echo'<div class="message_box"><div>'.$message_history.'</div></div>';} ?>
                    <form action="<?= $form_action ?>" method="POST">
                        <!-- Inbox form -->
                        <textarea maxlength="255" required class="form-control" rows="5" name="message" placeholder="Stuur een bericht naar <?= $receiver_name ?>"></textarea>
                        <br />
                        <input type="hidden" name="receiver" value="<?= $receiver_id ?>">
                        <input type="hidden" name="sender" value="<?= $sender_id ?>">
                        <input type="hidden" name="date" value="<?= date('Y-m-d H:i:s') ?>">
                        <button type="submit" class="btn btn-primary"><?= $submit_btn ?></button>
                        <a class="btn btn-secondary" href="">Vernieuwen</a>
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