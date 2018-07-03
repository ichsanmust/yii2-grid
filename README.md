Select (Checkbox) Grid view Extensions
===============
Extensions ini di peruntukan untuk kebutuhan select data di grid view yii2 dengan memakai checkbox.
permasalahan yang ada, jika kita memakai 'class' => 'yii\grid\CheckboxColumn' dari yii, ada kekurangan,
dimana saat kita telah melakukan select data, dan kita melakukan searching, filtering, sorting (pjax request), 
data selected yang sebelumnya menjadi hilang, dengan extension ini, dapat memperbaiki kekurangan tersebut.
semoga bermanfaat

Installation
------------

Disarankan Install melalui composer [composer](http://getcomposer.org/download/).

jalan kan perintah

```
php composer.phar require --prefer-dist ichsanmust/yii2-grid "@dev"
```

atau tambahkan

```
"ichsanmust/yii2-grid": "@dev"
```

di require section file `composer.json` . lalu jalan kan composer update


