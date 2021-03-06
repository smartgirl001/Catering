<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%shop}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $orgid
 * @property string $name
 * @property string $region_id
 * @property string $address
 * @property string $cookstyle_id
 * @property string $category_id
 * @property integer $default
 * @property string $business_license
 * @property string $legalperson
 * @property string $mobile
 * @property string $remark
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Shop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['user_id', 'cookstyle_id', 'category_id', 'default', 'status', 'created_at', 'updated_at'], 'integer'],
            [['orgid', 'name', 'region_id', 'address', 'business_license', 'legalperson', 'mobile', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'orgid' => '店铺机构编号',
            'name' => '店铺名称',
            'region_id' => '区域ID',
            'address' => '地址',
            'cookstyle_id' => '菜系ID',
            'category_id' => '店铺分类ID',
            'default' => '默认店铺',
            'business_license' => '工商执照',
            'legalperson' => '法人代表',
            'mobile' => '联系方式',
            'remark' => 'Remark',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /*
     * 店铺保存之前添加额外的数组
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (isset(Yii::$app->user->identity->id) && !empty(Yii::$app->user->identity->id)) {
                    $uid = Yii::$app->user->identity->id;
                } else {
                    $uid = 0;
                }

                $this->orgid = 'AAAAAAA';
                $this->user_id = $uid;
                $this->default = '0';
                $this->business_license = '';
                $this->legalperson = '';
                $this->mobile = '';
                $this->status = 1;
            }
            return true;
        } else {
            return false;
        }
    }

    /*
     * 获取 商铺类型
     */
    public function getShopcate()
    {
        return $this->hasOne(ShopCategory::className(), [
            'id' => 'category_id'
        ]);
    }

    /*
     * 获取 菜系
     */
    public function getCookstyle()
    {
        return $this->hasOne(Cookstyle::className(), [
            'id' => 'cookstyle_id'
        ]);
    }
}