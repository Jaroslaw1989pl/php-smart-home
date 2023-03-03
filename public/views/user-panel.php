<!-- css files -->
<link rel="stylesheet" type="text/css" href="/statics/styles/user-panel.css">

<!-- script files-->
<script type="text/javascript" src="/statics/scripts/auth-password-actions.js" defer></script>
<script type="text/javascript" src="/statics/scripts/user-panel.js" defer></script>

<!-- page content -->
<aside>
    <div class="aside-block profile-img-container">
        <div class="profile-img-container">
            <img src="<?php
                echo '/statics/img/profile-avatars/' . ($data['user']['avatar'] ?? 'default') . '.jpg';
            ?>" alt="profile avatar">
        </div>
    </div>
    <div class="aside-block">
        <span>Welcome</span>
        <span><?php
            if ($data['user']['firstName']) echo $data['user']['firstName'];
            else echo explode('@', $data['user']['email'])[0]
        ?></span>
    </div>
    <div class="aside-block">
        <button id="profile-btn">profile</button>
    </div>
    <div class="aside-block">
        <button id="settings-btn">settings</button>
    </div>
    <div class="aside-block">
        <form action="/logout" method="POST">
            <button type="submit" id="log-out-btn">Log out</button>
        </form>
    </div>
</aside>
<main>

    <!-- USER PROFILE SECTION -->
    <section id="profile-panel" class="section">

        <h1>My profile</h1>
        <p>Complete your profile</p>

        <div class="form-wrapper">

            <h3>User details</h3>

            <form action="/user-panel/profile" method="POST">

                <div class="form-section">
                    <div class="form-group">
                        <div id="img-box">
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-orange" value="orange" />
                                <label for="avatar-orange"><img src="/statics/img/profile-avatars/orange.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-blue" value="blue" />
                                <label for="avatar-blue"><img src="/statics/img/profile-avatars/blue.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-green" value="green" />
                                <label for="avatar-green"><img src="/statics/img/profile-avatars/green.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-yellow" value="yellow" />
                                <label for="avatar-yellow"><img src="/statics/img/profile-avatars/yellow.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-red" value="red" />
                                <label for="avatar-red"><img src="/statics/img/profile-avatars/red.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-dark-blue" value="dark-blue" />
                                <label for="avatar-dark-blue"><img src="/statics/img/profile-avatars/dark-blue.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-light-blue" value="light-blue" />
                                <label for="avatar-light-blue"><img src="/statics/img/profile-avatars/light-blue.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-beige" value="beige" />
                                <label for="avatar-beige"><img src="/statics/img/profile-avatars/beige.jpg" alt="" /></label>
                            </div>
                            <div class="img-input-group">
                                <input type="radio" class="avatar" name="avatar" id="avatar-purple" value="purple" />
                                <label for="avatar-purple"><img src="/statics/img/profile-avatars/purple.jpg" alt="" /></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-group">

                        <!-- USER FIRST NAME FIELD -->
                        <div class="form-input">
                            <?php if ($data['errors']['userFirstName']) : ?>
                                <p id="user-first-name-error" class="input-error"><?= $data['errors']['userFirstName'] ?></p>
                            <?php endif; ?>
                            <input type="text" id="user-first-name-input" class="text-input" name="userFirstName"
                                   placeholder="Your first name" value="<?=
                                $data['inputs']['userFirstName'] ? $data['inputs']['userFirstName'] : $data['user']['firstName']
                            ?>" />
                        </div>

                        <!-- USER LAST NAME FIELD -->
                        <div class="form-input">
                            <?php if ($data['errors']['userLastName']) : ?>
                                <p id="user-last-name-error" class="input-error"><?= $data['errors']['userLastName'] ?></p>
                            <?php endif; ?>
                            <input type="text" id="user-last-name-input" class="text-input" name="userLastName"
                                   placeholder="Your last name" value="<?php
                                echo $data['inputs']['userLastName'] ? $data['inputs']['userLastName'] : $data['user']['lastName']
                            ?>" />
                        </div>

                        <!-- USER PHONE NUMBER FIELD -->
                        <div class="form-input">
                            <?php if ($data['errors']['userPhone']) : ?>
                                <p id="user-phone-error" class="input-error"><?= $data['errors']['userPhone'] ?></p>
                            <?php endif; ?>
                            <input type="text" id="user-phone-input" class="text-input" name="userPhone"
                                   placeholder="Phone number" value="<?php
                                echo $data['inputs']['userPhone'] ? $data['inputs']['userPhone'] : $data['user']['phone']
                            ?>" />
                        </div>

                        <!-- USER EMAIL ADDRESS FIELD -->
                        <div class="form-input">
                            <input type="text" id="user-email-input" class="text-input" disabled
                                   value="<?= $data['user']['email'] ?>" />
                        </div>

                        <!-- USER LOCATION FIELD -->
                        <div class="form-input">
                            <?php if ($data['errors']['userLocation']) : ?>
                                <p id="user-userLocation-error" class="input-error"><?= $data['errors']['userLocation'] ?></p>
                            <?php endif; ?>
                            <input type="text" id="user-location-input" class="text-input" name="userLocation"
                                   placeholder="Your location" value="<?php
                                echo $data['inputs']['userLocation'] ? $data['inputs']['userLocation'] : $data['user']['location']
                            ?>" />
                        </div>
                        <div id="location-live-hints"></div>

                    </div>
                </div>

                <input type="submit" id="profile-submit-btn" class="auth-form-submit-btn" value="Update profile">

            </form>
        </div>

    </section>


<!-- USER SETTINGS SECTION -->
    <section id="settings-panel" class="section">

        <h1>Settings</h1>

        <!-- CHANGE PASSWORD FORM -->
        <div class="form-wrapper">
            <form action="/password-new" method="POST">

                <h2>Set up new password</h2>
                <p>Follow the instructions below</p>

                <div class="form-group">
                    <div class="form-input">
                        <?php if ($data['errors']['passOld']) : ?>
                            <p id="user-pass-old-error" class="input-error"><?= $data['errors']['passOld'] ?></p>
                        <?php endif; ?>
                        <input type="password" id="user-pass-old" class="pass-input" name="userPassOld" placeholder="Old password"
                               value="<?= $data['inputs']['userPassOld'] ?>" />
                        <input type="button" class="show-hide-btn hidden" id="user-pass-old-toggle" />
                    </div>

                    <div id="pass-form-input" class="form-input">
                        <?php if ($data['errors']['pass']) : ?>
                            <p id="user-pass-new-error" class="input-error"><?= $data['errors']['pass'] ?></p>
                        <?php endif; ?>
                        <input type="password" id="user-pass-new" class="pass-input" name="userPass" placeholder="New password"
                               value="<?= $data['inputs']['userPass'] ?>" />
                        <input type="button" class="show-hide-btn hidden" id="user-pass-toggle" />
                    </div>

                    <input type="submit" id="settings-submit-btn" class="auth-form-submit-btn" value="Update profile">

                </div>

                <div class="form-group" id="pass-requirements-container"></div>

            </form>
        </div>

        <!-- DELETE USER ACCOUNT FORM -->
        <div class="form-wrapper">
            <form action="/delete" method="POST">
                <h2>Delete Account</h2>
                <div>
                    <div class="form-group"><p>Delete your account and account data</p></div>
                    <div class="form-group">
                        <div id="del-form-submit-group" class="form-group">
                            <button id="delete-btn" class="auth-form-submit-btn">Delete my account</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>

</main>