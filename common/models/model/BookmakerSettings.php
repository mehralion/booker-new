<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 23.05.2016
 */

namespace common\models\model;

use common\models\Settings;
use yii\base\Model;

class BookmakerSettings extends Model
{
    public $min_time_decline = 0;
    public $max_percent_decline = 0;
    public $is_daily_limit = 1;
    public $deadline_notify_event = 5;
    public $kr_bank = 5;
    public $min_level = 0;
    public $autoapprove = false;

    public static function all()
    {
        $obj = new self();

        $SettingsList = Settings::find()
            ->andWhere('type = :type', [':type' => Settings::TYPE_BOOKMAKER])
            ->asArray()
            ->all();
        $attributes = [];
        foreach ($SettingsList as $Settings) {
            $attributes[$Settings['field']] = $Settings['value'];
        }

        $obj->setAttributes($attributes, false);

        return $obj;
    }

    public function save()
    {
        $t = \Yii::$app->db->beginTransaction();
        try {
            Settings::deleteAll('type = :type', [':type' => Settings::TYPE_BOOKMAKER]);

            $rows = [];
            foreach ($this->getAttributes() as $name => $value) {
                $rows[] = [
                    'field'         => $name,
                    'value'         => $value,
                    'updated_at'    => time(),
                    'type'          => Settings::TYPE_BOOKMAKER
                ];
            }

            \Yii::$app->db->createCommand()
                ->batchInsert(Settings::tableName(), (new Settings)->attributes(), $rows)->execute();

            $t->commit();

            return true;
        } catch (\Exception $ex) {
            $t->rollBack();
            //@TODO Logging
        }

        return false;
    }
}