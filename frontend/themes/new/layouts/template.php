<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 13.05.2016
 */
/* @var \common\components\View $this */
/** @var string $content */
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\bootstrap\Alert;

AppAsset::register($this);
$theme = $this->theme;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrapper">
    <?= $content ?>
</div>
<footer class="footer">

</footer><!-- .footer -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>