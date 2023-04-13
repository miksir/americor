<?php

namespace app\widgets\helpers\EventRender;

use app\models\Fax;
use app\models\History;
use Yii;
use yii\helpers\Html;

class FaxEventRender extends BaseEventRender
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
        $fax = $model->fax;
        return $this->widget->render('_item_common', [
            'user' => $model->user,
            'body' => $this->getMessage($model) . $this->getDocumentLink($fax),
            'footer' => $this->getFooterStr($fax),
            'footerDatetime' => $model->ins_ts,
            'iconClass' => 'fa-fax bg-green'
        ]);
    }

    /**
     * @param History $model
     * @param Fax|null $fax
     * @return string
     */
    private function getDocumentLink(?Fax $fax): string
    {
        $document = '';
        if (!is_null($fax) && isset($fax->document)) {
            $document = Html::a(
                Yii::t('app', 'view document'),
                $fax->document->getViewUrl(),
                [
                    'target' => '_blank',
                    'data-pjax' => 0
                ]);
        }
        return ($document ?: ' - ') . $document;
    }

    /**
     * @param Fax|null $fax
     * @return string
     */
    private function getFooterStr(?Fax $fax): string
    {
        return Yii::t('app', '{type} was sent to {group}', [
            'type' => $fax ? $fax->getTypeText() : 'Fax',
            'group' => isset($fax->creditorGroup) ? Html::a($fax->creditorGroup->name, ['creditors/groups'], ['data-pjax' => 0]) : ''
        ]);
    }

    public function getMessage(History $model): string
    {
        return $model->eventText;
    }
}