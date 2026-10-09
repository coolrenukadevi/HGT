<!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Sign in · Holiday Guru CMS</title>
<link rel="icon" type="image/png" sizes="32x32" href="/assets/brand/favicon-32.png">
<link rel="stylesheet" href="/cms-assets/cms.css?v=<?= filemtime(CMS_ROOT . '/public/cms-assets/cms.css') ?>">
</head>
<body class="cms cms-login">
<?php if (!cms_site_writes()) { ?><div class="cms-ribbon" role="note">Staging CMS — changes here do not reach the live website</div><?php } ?>
<main class="cms-login__wrap">
    <form class="cms-login__card" method="post" action="/login<?= get('next') ? '?next=' . e(rawurlencode(get('next'))) : '' ?>">
        <img class="cms-login__logo" src="/assets/brand/holiday-guru-travel-logo-script-540.png" alt="Holiday Guru Travel" width="248" height="44">
        <h1>Sign in to the CMS</h1>
        <p class="cms-muted">Tour packages, offers and enquiries.</p>
        <?php foreach (flash() as $f) { ?><p class="cms-flash cms-flash--<?= e($f[0]) ?>" role="status"><?= e($f[1]) ?></p><?php } ?>
        <?php if ($error) { ?><p class="cms-flash cms-flash--err" role="alert"><?= e($error) ?></p><?php } ?>
        <?= csrf_field() ?>
        <?= field_text('email', 'Email', $email, array('type' => 'email', 'required' => true, 'autocomplete' => 'username')) ?>
        <?= field_text('password', 'Password', '', array('type' => 'password', 'required' => true, 'autocomplete' => 'current-password')) ?>
        <button class="cms-btn cms-btn--primary cms-btn--block" type="submit">Sign in</button>
        <p class="cms-login__forgot"><a href="/forgot">Forgot password?</a></p>
    </form>
</main>
</body>
</html>
