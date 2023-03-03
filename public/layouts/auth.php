<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= $data['title'] ?></title>

        <!-- css files -->
        <link rel="stylesheet" type="text/css" href="/statics/styles/auth.css">

        <!-- script files-->

    </head>
    <body>

        <header id="top-bar"></header>

        <div id="page-content">
            <main id="auth">

                <div id="content-left">
                    <a href="/" id="home-link">Playfab</a>
                    <p>Sign in or create an account</p>
                </div>

                <div id="content-right">{{content}}</div>

            </main>
        </div>

    </body>
</html>