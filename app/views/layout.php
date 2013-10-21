<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if (isset($title)): echo $this->escape($title) . ' - '; endif; ?>Mopitter</title>
    <link rel="stylesheet" type="text/css" media="screen" href="/miniblog/css/style.css" />
</head>
<body>
    <div id="header">
        <h1><a href="<?php echo $baseUrl; ?>/">Mopitter</a></h1>
    </div>

    <div id="nav">
        <p>
            <?php if ($session->isAuthenticated()): ?>
            <a href="<?php echo $baseUrl; ?>/">ホーム</a>
            <a href="<?php echo $baseUrl; ?>/account">アカウント</a>
            <a href="<?php echo $baseUrl; ?>/account/signout">ログアウト</a>
            <?php else: ?>
            <a href="<?php echo $baseUrl; ?>/account/signin">ログイン</a>
            <a href="<?php echo $baseUrl; ?>/account/signup">アカウント登録</a>
            <?php endif; ?>
        </p>
    </div>

    <div id="main">
        <?php echo $_content; ?>
    </div>
</body>
</html>
