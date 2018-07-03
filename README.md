Select (Checkbox) Grid view Extensions
===============
Extensions ini diperuntukan untuk kebutuhan select data multiple di grid view yii2 dengan memakai checkbox.
permasalahan yang ada, jika kita memakai 'class' => 'yii\grid\CheckboxColumn' dari yii, ada kekurangan,
dimana saat kita telah melakukan select data, dan kita melakukan searching, filtering, sorting, paging (pjax request), 
data selected yang sebelumnya menjadi hilang, dengan extension ini, dapat memperbaiki kekurangan tersebut.
semoga bermanfaat...

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

Contoh Penggunaan 
------------


// controller 

<?php
public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
?>

// view 

<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>



	<?php
		\yii\widgets\Pjax::begin([
			'id'=>'pjax-product-gridview',
			'enablePushState'=>false,
		]); 
	?>
	
    <?= GridView::widget([
		'id'=>'crud-gridview-ichsanmust',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			[   
					'class' => 'ichsanmust\grid\CheckboxColumnSelectTools', 
					'name'=>'choose', // nama untuk checkbox
					'uniqueValue'=>'id_product', // value unique yang akan di select
					//'valueInit'=> array(3,2), // value data selected saat inisiasi gridview
					//'disabledCheckboxOnValue' =>true, // saat valueInit ada datanya, checkbox yang ter select akan di disabled
					//'checkedCheckboxOnValue' => true, // saat valueInit ada datanya, checkbox yang ter select akan di checked
						
					
			],
            'id_product',
            'product_name',
            'id_product_category',
            'stok',
			
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
	<?php \yii\widgets\Pjax::end(); ?>
	<?php
	$this->registerJs(
		   '
			$("#pjax-product-gridview").on("pjax:send", function() { // beforeSend
						
			})
			$("#pjax-product-gridview").on("pjax:complete", function() { // complete
				retainCheckedSingle(); // ini harus di deklarasikan 
				setCheckedChooseAll(); // ini harus di deklarasikan 
			})
			'
		);
	?>
				
				
				

<a id ="getSelected" class ="btn btn-success" > Get Selected Value </a>
<?php

$this->registerJs(
	   '
		jQuery(document).on("click","#getSelected",function(e){
			console.log(getListChecked()); // ini yang di olah
			alert(getListChecked()); // ini yang di olah
			return false;
		});
		'
	);


?>



