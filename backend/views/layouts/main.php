<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'TacticGame.Es',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' =>Yii::t('app','Login'), 'url' => ['/site/login']];
            } else {
                $menuItems = [
                    ['label' => Yii::t('app','Items'), 'url' => ['/items/index']],
                    ['label' => Yii::t('app','Sections'), 'url' => ['/sections/index']],
                    ['label' => Yii::t('app','Position'), 'url' => ['/position/index']],
                    ['label' => Yii::t('app','Grafic'), 'url' => ['/grafic/index']],
                    ['label' => Yii::t('app','Transaction'), 'url' => ['/transaction/index']],
                    ['label' => Yii::t('app','Orders'), 'url' => ['/order/index']],
                    ['label' => Yii::t('app','Gallery'), 'url' => ['/galleryalbum/index']],
                    ['label' => Yii::t('app','Certification'), 'url' => ['/certification/index']],
                    ['label' => Yii::t('app','C. Order'), 'url' => ['/certificatorder/index']],
                    ['label' => Yii::t('app','Coupon'), 'url' => ['/coupon/index']],
//                    ['label' => Yii::t('app','Certf. orders'), 'url' => ['/certforders/index']]
                 ];

                $menuItems[] = [
                    'label' => Yii::t('app','Logout').' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left"><?= date('Y') ?> &copy; TacticGame.Es </p>
        <p class="pull-right">Powered by <a href="http://kholmatov.com">Kholmatov</a></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
