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
        $newWith = [];
        $populatePolymorph = [];
        foreach ($with as $name => $callback) {
            $relName = is_int($name) ? $callback : $name;
            if (isset(self::$relations[$relName])) {
                $populatePolymorph[$relName] = 1;
            } else {
                $newWith[$name] = $callback;
            }
        }
        /** @var History[][] $groupModels */
        $groupModels = [];
        foreach ($models as $model) {
            $groupModels[static::$objectRelMap[$model->object] ?? $model->object][] = $model;
        }
        /** @var History $model */
        $model = reset($models);
        foreach (self::$relations as $rel=>$className) {
            if (!isset($populatePolymorph[$rel])) {
                continue;
            }
            if (!isset($groupModels[$rel])) {
                $groupModels[$rel] = [];
            }
            $relation = $model->hasOne($className, ['id' => 'object_id']);
            $relation->primaryModel = null;
            $relation->asArray($this->asArray);
            $relation->populateRelation($rel, $groupModels[$rel]);
        }
        parent::findWith($newWith, $models);
    }
}