<?php

namespace app\widgets\helpers\EventRender;

use app\models\Customer;
use app\models\History;

class CustomerEventRender extends BaseEventRender
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
        return $this->widget->render('_item_statuses_change', array_merge([
            'footerDatetime' => $model->ins_ts,
            'eventText' => $model->eventText,
            // @TODO check link
            'username' => $model->user->username ?? ''
        ], $this->getValues($model)));
    }

    private function getValues(History $model): array
    {
        if ($model->event === History::EVENT_CUSTOMER_CHANGE_TYPE) {
            return [
                'oldValue' => Customer::getTypeTextByType($model->getDetailOldValue('type')) ?: "not set",
                'newValue' => Customer::getTypeTextByType($model->getDetailNewValue('type')) ?: "not set"
            ];
        }

        if ($model->event === History::EVENT_CUSTOMER_CHANGE_QUALITY) {
            return [
                'oldValue' => Customer::getQualityTextByQuality($model->getDetailOldValue('quality')) ?: "not set",
                'newValue' => Customer::getQualityTextByQuality($model->getDetailNewValue('quality')) ?: "not set",
            ];
        }

        return ['oldValue' => "not set", 'newValue' => "not set"];
    }

    public function getMessage(History $model): string
    {
        $values = $this->getValues($model);
        return $model->eventText . " " . $values['oldValue'] . " to " . $values['newValue'];
    }
}