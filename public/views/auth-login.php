<!-- css files -->

<!-- script files-->
<script type="text/javascript" src="/statics/scripts/auth-password-actions.js" defer></script>

<!-- page content -->
<div id="login-panel" class="auth-panel">

    <header class="auth-panel-header">
        <h1>Sign in</h1>
        <p>New user? <a href="/registration">Create an account</a></p>
    </header>

    <?php if ($data['errors']) : ?>
        <p id="login-form-error" class="form-error"><?= $data['errors'] ?></p>
    <?php endif; ?>

    <form action="/login" method="POST">

        <div id="user-email-input-container" class="form-input">
            <input type="text" id="user-email" class="text-input" name="userEmail" placeholder="Email address"
                   value="<?= $data['inputs']['userEmail'] ?>" />
        </div>

        <div class="form-input">
            <input type="password" id="user-pass" class="pass-input" name="userPass" placeholder="Password"
                   value="<?= $data['inputs']['userPass'] ?>" />
            <input type="button" class="show-hide-btn hidden" id="user-pass-toggle" />
        </div>

        <input type="submit" id="login-submit-btn" class="auth-form-submit-btn" value="Sign in">

    </form>

    <p>Forgot password? <a href="/password-reset">Reset password</a></p>

</div>