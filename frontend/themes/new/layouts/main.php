<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 13.05.2016
 */
/** @var \common\components\View $this */
?>

<?php $this->beginContent('@frontend/views/layouts/template.php') ?>
<header class="header">
    <div id="logo"></div>
    <?= \yii\widgets\Menu::widget([
        'options' => [
            'class' => 'nav',
            'id' => 'top-menu',
        ],
        'activateParents' => true,
        'items' => [
            [
                'label' => 'Главная',
                'url' => ['/site/index'],
                'template' => '<a href="{url}">{label}</a>'
            ],
            [
                'label' => 'Биржа',
                'url' => '#',
                'template' => '<a href="{url}">{label}</a>'
            ],
            [
                'label' => 'Казино',
                'url' => '#',
                'template' => '<a href="{url}">{label}</a>'
            ],
            [
                'label' => 'Лото',
                'url' => '#',
                'template' => '<a href="{url}">{label}</a>'
            ],
            [
                'label' => 'Ставки',
                'url' => '#',
                'template' => '<a href="{url}">{label}</a>'
            ],
            [
                'label' => 'Споры',
                'url' => '#',
                'template' => '<a href="{url}">{label}</a>'
            ],
            [
                'label' => 'Рейтинг',
                'url' => '#',
                'template' => '<a href="{url}">{label} </a>',
            ],
        ],
    ]); ?>
</header>
<div class="middle">
    <div class="container">
        <main class="content">
            <strong>Content:</strong> Sed placerat accumsan ligula. Aliquam felis magna, congue quis, tempus eu, aliquam vitae, ante. Cras neque justo, ultrices at, rhoncus a, facilisis eget, nisl. Quisque vitae pede. Nam et augue. Sed a elit. Ut vel massa. Suspendisse nibh pede, ultrices vitae, ultrices nec, mollis non, nibh. In sit amet pede quis leo vulputate hendrerit. Cras laoreet leo et justo auctor condimentum. Integer id enim. Suspendisse egestas, dui ac egestas mollis, libero orci hendrerit lacus, et malesuada lorem neque ac libero. Morbi tempor pulvinar pede. Donec vel elit.
        </main><!-- .content -->
    </div><!-- .container-->

    <aside class="left-sidebar">
        <?= \yii\widgets\Menu::widget([
            'options' => [
                'class' => 'nav metismenu side-menu',
                'id' => 'left-menu',
            ],
            'activateParents' => true,
            'items' => [
                [
                    'label' => 'Меню 1',
                    'template' => '<span class="title-left"></span><span class="title-center">{label}</span><span class="title-right"></span>',
                    'options' => [
                        'class' => 'title'
                    ]
                ],
                [
                    'label' => 'Главная',
                    'url' => ['/site/index'],
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Биржа',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Казино',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Лото',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Ставки',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Споры',
                    'url' => '#',
                    'template' => '<a href="{url}"><span class="nav-label">{label}</span><span class="fa arrow"></span></a>',
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\" style=\"height: 0px;\">\n{items}\n</ul>\n",
                    'items' => [
                        [
                            'label' => 'Описание',
                            'url' => ['/rate/item/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                        [
                            'label' => 'Описание',
                            'url' => ['/rate/item/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                        [
                            'label' => 'Описание',
                            'url' => ['/rate/item/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                    ],
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Лотерея',
                    'url' => '#',
                    'template' => '<a href="{url}"><span class="nav-label">{label}</span><span class="fa arrow"></span></a>',
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\" style=\"height: 0px;\">\n{items}\n</ul>\n",
                    'items' => [
                        [
                            'label' => 'Списки призов',
                            'url' => ['/loto/pocket/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                        [
                            'label' => 'Статистика',
                            'url' => ['/loto/stat/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                    ],
                    'options' => [
                        'class' => 'item'
                    ],
                ],
            ],
        ]); ?>
        <?= \yii\widgets\Menu::widget([
            'options' => [
                'class' => 'nav metismenu side-menu',
            ],
            'activateParents' => true,
            'items' => [
                [
                    'label' => 'Меню',
                    'template' => '<span class="title-left"></span><span class="title-center">{label}</span><span class="title-right"></span>',
                    'options' => [
                        'class' => 'title'
                    ]
                ],
                [
                    'label' => 'Главная',
                    'url' => ['/site/index'],
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Результаты событий',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'История ставок',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Ввод\Вывод',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'FAQ',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
            ],
        ]); ?>
    </aside><!-- .left-sidebar -->

    <aside class="right-sidebar">
        <?= \yii\widgets\Menu::widget([
            'options' => [
                'class' => 'nav metismenu side-menu',
            ],
            'activateParents' => true,
            'items' => [
                [
                    'label' => 'Меню 1',
                    'template' => '<span class="title-left"></span><span class="title-center">{label}</span><span class="title-right"></span>',
                    'options' => [
                        'class' => 'title'
                    ]
                ],
                [
                    'label' => 'Главная',
                    'url' => ['/site/index'],
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Биржа',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Казино',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Лото',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Ставки',
                    'url' => '#',
                    'template' => '<a href="{url}">{label}</a>',
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Споры',
                    'url' => '#',
                    'template' => '<a href="{url}"><span class="nav-label">{label}</span><span class="fa arrow"></span></a>',
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\" style=\"height: 0px;\">\n{items}\n</ul>\n",
                    'items' => [
                        [
                            'label' => 'Описание',
                            'url' => ['/rate/item/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                        [
                            'label' => 'Описание',
                            'url' => ['/rate/item/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                        [
                            'label' => 'Описание',
                            'url' => ['/rate/item/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                    ],
                    'options' => [
                        'class' => 'item'
                    ]
                ],
                [
                    'label' => 'Лотерея',
                    'url' => '#',
                    'template' => '<a href="{url}"><span class="nav-label">{label}</span><span class="fa arrow"></span></a>',
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\" style=\"height: 0px;\">\n{items}\n</ul>\n",
                    'items' => [
                        [
                            'label' => 'Списки призов',
                            'url' => ['/loto/pocket/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                        [
                            'label' => 'Статистика',
                            'url' => ['/loto/stat/index'],
                            'template' => '<a href="{url}"><i class="list-dot"></i> {label}</a>'
                        ],
                    ],
                    'options' => [
                        'class' => 'item'
                    ],
                ],
            ],
        ]); ?>
    </aside><!-- .right-sidebar -->
</div>

<?php $this->endContent(); ?>