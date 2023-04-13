<?php
use app\models\search\HistorySearch;
use app\widgets\helpers\EventRender\EventRenderFabricMethod;

/* @var $this yii\web\View */
/** @var $model HistorySearch */

$render = EventRenderFabricMethod::getInstance($this);
echo $render->getEventRender($model)->renderListItem($model);
