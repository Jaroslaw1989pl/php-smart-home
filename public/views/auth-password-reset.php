<!-- css files -->

<!-- script files-->

<!-- page content -->
<div id="pass-reset-panel" class="auth-panel">

    <?php if ($data['requestSuccess']): ?>

        <header class="auth-panel-header">
            <h1>Reset your password</h1>
            <p>Authentication email sent.</p>
            <p>Check your mailbox and if email does not appear within a few minutes, check your spam folder.</p>
        </header>

    <?php else: ?>

        <header class="auth-panel-header">
            <h1>Reset your password</h1>
            <p>To reset your password, please enter your email address.</p>
        </header>

        <?php if ($data['errors']) : ?>
            <p id="pass-reset-form-error" class="form-error"><?= $data['errors'] ?></p>
        <?php endif; ?>

        <form action="/password-reset" method="POST">

            <div id="user-email-input-container" class="form-input">
                <input type="text" id="user-email" class="text-input" name="userEmail" placeholder="Email address"
                       value="<?= $data['inputs']['userEmail'] ?>" />
            </div>

            <input type="submit" id="pass-reset-submit-btn" class="auth-form-submit-btn" value="Submit">

        </form>

        <p>Try to <a href="/login">sign in to a different account</a>?</p>

    <?php endif; ?>

</div>