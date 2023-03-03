<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= $data['title'] ?></title>

        <!-- css files -->
        <link rel="stylesheet" type="text/css" href="/statics/styles/main.css">

        <!-- script files-->
        <script type="text/javascript" src="/statics/scripts/main-actions.js" defer></script>

    </head>
    <body>

        <header id="top-bar">

            <div id="top-bar-left">
                <a href="/" class="img-home-link"><img src="/statics/img/logo.png" alt="logo" /></a>
                <a href="/" class="home-link">Playfab</a>
            </div>

            <div id="top-bar-right">

                <?php if ($data['user']): ?>

                    <div id="user-navigation">

                        <img src="<?php
                            echo '/statics/img/profile-avatars/' . ($data['user']['avatar'] ?? 'default') . '.jpg';
                        ?>" alt="profile avatar" class="profile-avatar">

                        <a href="" id="dropdown-toggle" onClick=""><?php
                            if ($data['user']['firstName']) echo $data['user']['firstName'];
                            else echo $data['user']['email']
                        ?> &#x21B4;</a>

                        <div id="user-menu" class="dropdown-menu">
                            <div class="dropdown-menu-group">
                                <div class="menu-item">
                                    <a href="/user-panel/profile">Profile</a>
                                </div>
                            </div>
                            <div class="dropdown-menu-group">
                                <div class="menu-item">
                                    <a href="/user-panel/settings">Settings</a>
                                </div>
                            </div>
                            <div class="dropdown-menu-group">
                                <div class="menu-item">
                                    <form action="/logout" method="POST">
                                        <button type="submit" id="logout-btn" onClick="">Log out</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                <?php else: ?>

                    <div id="sign-buttons">
                        <a href="/login"><button id="sign-in-btn" class="sign-btn">Sign in</button></a>
                        <a href="/registration"><button id="sign-up-btn" class="sign-btn">Sign up</button></a>
                    </div>

                <?php endif; ?>

            </div>

        </header>

        <!-- FLASH MESSAGES -->
        <ul id="flash-messages-list"><?php
            foreach ($data['flash'] as $flash)
                echo <<< FLASH
                    <li onclick="this.remove()">
                        <div class="flash-message-{$flash['type']}">{$flash['message']}</div>
                    </li>
                FLASH;
        ?></ul>

        <div id="page-content">{{content}}</div>

    </body>
</html>