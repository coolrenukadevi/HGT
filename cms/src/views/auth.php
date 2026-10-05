<!doctype html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title) ?> · Holiday Guru CMS</title>
<link rel="icon" type="image/png" sizes="32x32" href="/assets/brand/favicon-32.png">
<link rel="stylesheet" href="/cms-assets/cms.css?v=<?= filemtime(CMS_ROOT . '/public/cms-assets/cms.css') ?>">
</head>
<body class="cms cms-login">
<?php if (cms_is_staging()) { ?><div class="cms-ribbon" role="note">Staging CMS — changes here do not reach the live website</div><?php } ?>
<main class="cms-login__wrap">
    <div class="cms-login__card">
        <img class="cms-login__logo" src="/assets/brand/holiday-guru-travel-logo-script-540.png" alt="Holiday Guru Travel" width="248" height="44">
        <h1><?= e($title) ?></h1>
        <?= $body ?>
    </div>
</main>
</body>
</html>
