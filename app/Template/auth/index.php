<?/*
<div class="form-login">

    <?php // $this->hook->render('template:auth:login-form:before') ?>

    <?php if (isset($errors['login'])): ?>
        <p class="alert alert-error"><?= $this->text->e($errors['login']) ?></p>
    <?php endif ?>

    <?php /* if (! HIDE_LOGIN_FORM): ?>
      <form method="post" action="<?= $this->url->href('auth', 'check') ?>">

      <?= $this->form->csrf() ?>

      <?= $this->form->label(t('Username'), 'username') ?>
      <?= $this->form->text('username', $values, $errors, array('autofocus', 'required')) ?>

      <?= $this->form->label(t('Password'), 'password') ?>
      <?= $this->form->password('password', $values, $errors, array('required')) ?>

      <?php if (isset($captcha) && $captcha): ?>
      <?= $this->form->label(t('Enter the text below'), 'captcha') ?>
      <img src="<?= $this->url->href('Captcha', 'image') ?>"/>
      <?= $this->form->text('captcha', array(), $errors, array('required')) ?>
      <?php endif ?>

      <?php if (REMEMBER_ME_AUTH): ?>
      <?= $this->form->checkbox('remember_me', t('Remember Me'), 1, true) ?><br>
      <?php endif ?>

      <div class="form-actions">
      <button type="submit" class="btn btn-blue"><?= t('Sign in') ?></button>
      </div>
      <?php if ($this->app->config('password_reset') == 1): ?>
      <div class="reset-password">
      <?= $this->url->link(t('Forgot password?'), 'PasswordReset', 'create') ?>
      </div>
      <?php endif ?>
      </form>
      <?php endif 

    
</div>*/ ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Sonder: Development space</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--[if lte IE 8]><script src="assets/js/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="assets/css/main.css" />
        <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
    </head>
    <body class="is-loading">
        <!-- Wrapper -->
        <div id="wrapper">
            <!-- Main -->
            <section id="main">
                <div>
                    <span class="avatar"><a href="http://2sonder.com"><img src="images/sonder.png" alt="" /></a></span>
                    <h1><a href="http://2sonder.com">Online story tellers</a></h1>
                    <p><?= $this->hook->render('template:auth:login-form:after') ?></p>
                     <?php if (isset($errors['login'])): ?>
        <p class="alert alert-error"><?= $this->text->e($errors['login']) ?></p>
    <?php endif ?>
        <div>
            <footer>
                    <ul class="icons">
                        <li><a href="https://twitter.com/2sonder" class="fa-twitter">Twitter</a></li>
                        <li><a href="https://www.linkedin.com/company/2sonder" class="fa-linkedin">Linkedin</a></li>
                        <li><a href="https://www.facebook.com/2Sonder/" class="fa-facebook">Facebook</a></li>
                    </ul>
                </footer>
            </section>
            <footer id="footer"></footer>
        </div>
        <!-- Scripts -->
                <!--[if lte IE 8]><script src="assets/js/respond.min.js"></script><![endif]-->
        <script src="assets/js/footer.js"></script>
    </body>
</html>