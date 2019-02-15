<?php
/**
 * Created by PhpStorm.
 * User: kholmatov
 * Date: 26/05/15
 * Time: 00:27
 */
use yii\jui\Menu;

            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();

echo Menu::widget([
     'items' => [
         ['label' => Yii::t('app','Main'), 'url' => ['site/index']],
         ['label' => Yii::t('app','Gallery'), 'url' => ['gallery/index']],
         ['label' => Yii::t('app','Bonus gift'), 'url' => ['certification/1']],
         ['label' => Yii::t('app','FAQ'), 'url' => ['items/3']],
    ],
]);

        ?>

<div class="container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>
</div>
</div>

<!--    <footer class="footer">-->
<!--        <div class="container">-->
<!--        <p class="pull-left">&copy; My Company --><?//= date('Y') ?><!--</p>-->
<p class="pull-right"><?= Yii::powered() ?></p>
<!--        </div>-->
<!--    </footer>-->