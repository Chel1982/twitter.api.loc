<?php

namespace app\models;

use yii\base\Model;

/**
 * UserSearch is the model behind the user search form.
 */
class UserSearch extends Model
{
    public $usersearch;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['usersearch'], 'string', 'max' => 255],
            [['usersearch'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'usersearch' => 'Поиск пользователя в Twitter',
        ];
    }
}
