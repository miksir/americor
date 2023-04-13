<?php

namespace app\widgets\helpers\EventRender;

use app\models\History;

class TaskEventRender extends BaseEventRender
{
    /**
     * @var \yii\web\View
     */
    private $widget;

    public function __construct(\yii\web\View $widget)
    {
        $this->widget = $widget;
    }

    public function renderListItem(History $model): string
    {
        $task = $model->task;
        return $this->widget->render('_item_common', [
            'user' => $model->user,
            'body' => $this->getMessage($model),
            'iconClass' => 'fa-check-square bg-yellow',
            'footerDatetime' => $model->ins_ts,
            'footer' => isset($task->customerCreditor->name) ? "Creditor: " . $task->customerCreditor->name : ''
        ]);
    }

    public function getMessage(History $model): string
    {
        return $model->eventText . ": " . ($model->task->title ?? '');
    }
}