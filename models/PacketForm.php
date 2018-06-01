<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PacketForm extends Model
{
    public $quantity;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['quantity'], 'required'],
            [['quantity'], 'integer']
        ];
    }
}
