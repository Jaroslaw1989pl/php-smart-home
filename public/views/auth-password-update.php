<!-- css files -->

<!-- script files-->
<script type="text/javascript" src="/statics/scripts/auth-password-actions.js" defer></script>

<!-- page content -->
<div id="pass-update-panel" class="auth-panel">

    <?php if ($data['requestSuccess']): ?>

        <header class="auth-panel-header">
            <h1>Reset your password</h1>
            <p>Password updated successfully.</p>
        </header>

    <?php else: ?>

        <header class="auth-panel-header">
            <h1>Set your new password</h1>
            <p>Create password, that meets requirements.</p>
        </header>

        <form action="/password-update" method="POST">

            <input type="text" name="token" value="<?= $data['inputs']['token'] ?>" hidden />

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

            <input type="submit" id="pass-update-submit-btn" class="auth-form-submit-btn" value="Submit">

        </form>

        <p>Try to <a href="/login">sign in to a different account</a>?</p>

    <?php endif; ?>

</div>