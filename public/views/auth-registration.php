<!-- css files -->

<!-- script files-->
<script type="text/javascript" src="/statics/scripts/auth-password-actions.js" defer></script>

<!-- page content -->
<div id="registration-panel" class="auth-panel">

    <?php if ($data['requestSuccess']): ?>

        <header class="auth-panel-header">
            <h1>Account created</h1>
        </header>

    <?php else: ?>

        <header class="auth-panel-header">
            <h1>Create an account</h1>
            <p>Already have an account? <a href="/login">Sign in</a></p>
        </header>

        <form action="/registration" method="POST">

            <div id="user-email-input-container" class="form-input">

                <?php if ($data['errors']['email']) : ?>
                    <p id="user-email-error" class="input-error"><?= $data['errors']['email'] ?></p>
                <?php endif; ?>

                <input type="text" id="user-email" class="text-input" name="userEmail" placeholder="Email address"
                       value="<?= $data['inputs']['userEmail'] ?>" />
            </div>

            <div id="pass-form-input" class="form-input">

                <?php if ($data['errors']['pass']) : ?>
                    <p id="user-pass-error" class="input-error"><?= $data['errors']['pass'] ?></p>
                <?php endif; ?>

                <input type="password" id="user-pass-new" class="pass-input" name="userPass" placeholder="Password"
                       value="<?= $data['inputs']['userPass'] ?>" />
                <input type="button" class="show-hide-btn hidden" id="user-pass-toggle" />
            </div>
            <div id="pass-requirements-container"></div>

            <div class="form-input">

                <?php if ($data['errors']['passConf']) : ?>
                    <p id="user-pass-conf-error" class="input-error"><?= $data['errors']['passConf'] ?></p>
                <?php endif; ?>

                <input type="password" id="user-pass-conf" class="pass-input" name="userPassConf" placeholder="Confirm password"
                       value="<?= $data['inputs']['userPassConf'] ?>" />
                <input type="button" class="show-hide-btn hidden" id="user-pass-conf-toggle" />
            </div>

            <input type="submit" id="registration-submit-btn" class="auth-form-submit-btn" value="Sign up">

        </form>

    <?php endif; ?>

</div>