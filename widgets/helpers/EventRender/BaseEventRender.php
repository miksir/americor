<?php

namespace app\widgets\helpers\EventRender;

use app\models\History;

abstract class BaseEventRender
{
    /**
     * Render item of history for history list view
     * @param History $model
     * @return string
     */
    public abstract function renderListItem(History $model): string;

    /**
     * Render item of history to text for csv export
     * @param History $model
     * @return string
     */
    public abstract function getMessage(History $model): string;
}