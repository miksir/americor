<?php

namespace app\widgets\helpers\EventRender;

use app\models\History;

class DefaultEventRender extends BaseEventRender
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
        return $this->widget->render('_item_common', [
            'user' => $model->user,
            'body' => $this->getMessage($model),
            'bodyDatetime' => $model->ins_ts,
            'iconClass' => 'fa-gear bg-purple-light'
        ]);
    }

    public function getMessage(History $model): string
    {
        return $model->eventText;
    }
}