<?php

namespace app\widgets\helpers\EventRender;

use app\models\Call;
use app\models\History;

class CallEventRender extends BaseEventRender
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
        $call = $model->call;
        $answered = isset($call) && $call->status == Call::STATUS_ANSWERED;

        return $this->widget->render('_item_common', [
            'user' => $model->user,
            'content' => $call->comment ?? '',
            'body' => $this->getMessage($model),
            'footerDatetime' => $model->ins_ts,
            'footer' => isset($call->applicant) ? "Called <span>{$call->applicant->name}</span>" : null,
            'iconClass' => $answered ? 'md-phone bg-green' : 'md-phone-missed bg-red'
        ]);
    }

    /**
     * @param History $model
     * @return string
     */
    public function getMessage(History $model): string
    {
        $call = $model->call;
        if ($call) {
            return $call->totalStatusText
                . ($call->getTotalDisposition(false) ?
                    " <span class='text-grey'>" . $call->getTotalDisposition(false) . "</span>" : "");
        } else {
            return '<i>Deleted</i>';
        }
    }
}