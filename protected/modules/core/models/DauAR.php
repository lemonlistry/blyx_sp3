<?php

/**
 * This is the model class for table "dau".
 *
 * The followings are the available columns in table 'dau':
 * @property integer $id
 * @property integer $server_id
 * @property string $ts
 * @property string $openid
 */
class DauAR extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DauAR the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'dau';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('server_id, openid', 'required'),
            array('server_id', 'numerical', 'integerOnly'=>true),
            array('openid', 'length', 'max'=>32),
            array('ts', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, server_id, ts, openid', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'server_id' => 'Server',
            'ts' => 'Ts',
            'openid' => 'Openid',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('server_id',$this->server_id);
        $criteria->compare('ts',$this->ts,true);
        $criteria->compare('openid',$this->openid,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}