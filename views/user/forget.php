<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\ActiveForm;

/* @var \app\components\View $this */
/* @var \app\models\forms\ForgetForm $model */

$this->title = 'Восстановление пароля';
$this->params['page'] = 'login';
?>
<div class="page-login">
    <div class="formbox formbox-medium formbox-center">
        <div class="formbox-content">
            <?php $form = ActiveForm::begin() ?>
            <?= $form->errorSummary($model, [
                'header' => '<p><strong>Исправьте следующие ошибки:</strong></p>',
                'class' => 'form-warning',
            ]) ?>
            <?= $form->field($model, 'email')
                ->label('Электронная почта') ?>
            <?= Html::submitButton('Восстановить пароль', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end() ?>
        </div>
        <div class="formbox-footer">
            <p>Нет учетной записи? <a href="<?= Url::toRoute('user/registration') ?>">Присоединяйтесь к нам прямо сейчас!</a></p>
            <p>У вас уже есть учетная запись? <a href="<?= Url::toRoute('user/login') ?>">Пожалуйста авторизуйтесь.</a></p>
        </div>
    </div>
</div>
