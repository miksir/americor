<?php

namespace app\widgets\helpers\EventRender;

use app\models\History;
use app\models\Sms;
use Yii;

class SmsEventRender extends BaseEventRender
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
        $sms = $model->sms;
        return $this->widget->render('_item_common', [
            'user' => $model->user,
            'body' => isset($sms) ? $this->getMessage($model) : '<i>Deleted</i>',
            'footer' => $this->getDirectionStr($sms),
            'footerDatetime' => $model->ins_ts,
            'iconClass' => 'icon-sms bg-dark-blue'
        ]);
    }

    /**
     * @param Sms|null $sms
     * @return string
     */
    private function getDirectionStr(?Sms $sms): string
    {
        if (is_null($sms)) {
            return '';
        }
        if ($sms->direction === Sms::DIRECTION_INCOMING) {
            return Yii::t('app', 'Incoming message from {number}', [
                'number' => $sms->phone_from ?? ''
            ]);
        }
        if ($sms->direction === Sms::DIRECTION_OUTGOING) {
            return Yii::t('app', 'Sent message to {number}', [
                'number' => $sms->phone_to ?? ''
            ]);
        }
        return '<i>Unknown direction</i>';
    }

    public function getMessage(History $model): string
    {
        return isset($model->sms) ? $model->sms->message : '';
    }
}