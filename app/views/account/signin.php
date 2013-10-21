<?php $this->setLayoutVar('title', 'ログイン'); ?>

<h2>ログイン</h2>

<p>
    <a href="<?php echo $baseUrl; ?>/account/signup">新規ユーザー登録</a>
</p>

<form action="<?php echo $baseUrl; ?>/account/authenticate" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

    <?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('common/errors', array('errors' => $errors)); ?>
    <?php endif; ?>
    <?php echo $this->render('common/inputs', array('userName' => $userName, 'password' => $password)); ?>
    <p>
        <input type="submit" value="ログイン" />
    </p>
</form>

