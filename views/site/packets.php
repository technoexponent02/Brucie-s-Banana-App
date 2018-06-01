<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Brucie\'s Banana Bazaar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'packets-form']); ?>
            <div class="formArea">

                <?= $form->field($model, 'quantity')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
       
            <?php if (count($combinations_of_packets) > 0){ ?>
            <div>
                <h3>No. of Bananas: <small><?php echo $no_of_bananas; ?></small></h3>
            
                <table class="table">
                    <thead class="thead-dark">
                        <th>Quantity</th>
                        <th>No of. Packets</th>
                    </thead>
                    <?php foreach ($combinations_of_packets as $packet_quantity => $no_of_packets){ ?>
                    <tbody>
                        <td><?php echo str_replace("'", "", $packet_quantity); ?></td>
                        <td><?php echo $no_of_packets; ?></td>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>
        </div> 
    </div>
</div>
