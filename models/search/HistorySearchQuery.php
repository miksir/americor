<?php

namespace app\models\search;

use app\models\Call;
use app\models\Fax;
use app\models\History;
use app\models\Sms;
use app\models\Task;

class HistorySearchQuery extends \yii\db\ActiveQuery
{
    static private $relations = [
        'sms' => Sms::class,
        'call' => Call::class,
        'task' => Task::class,
        'fax' => Fax::class
    ];
    static private $objectRelMap = [
        'call_ytel' => 'call'
    ];

    /**
     * @param $with
     * @param History[] $models
     * @return void
     */
    public function findWith($with, &$models)
    {
        /** @var History[][] $groupModels */
        $groupModels = [];
        foreach ($models as $model) {
            $groupModels[static::$objectRelMap[$model->object] ?? $model->object][] = $model;
        }
        /** @var History $model */
        $model = reset($models);
        foreach (self::$relations as $rel=>$className) {
            if (!isset($groupModels[$rel])) {
                $groupModels[$rel] = [];
            }
            $relation = $model->hasOne($className, ['id' => 'object_id']);
            $relation->primaryModel = null;
            $relation->asArray($this->asArray);
            $relation->populateRelation($rel, $groupModels[$rel]);
        }
        parent::findWith($with, $models);
    }
}