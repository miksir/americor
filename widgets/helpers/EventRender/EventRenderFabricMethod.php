<?php

namespace app\widgets\helpers\EventRender;

use app\models\History;

class EventRenderFabricMethod
{
    private static $classes = [
        'default' => DefaultEventRender::class,
        'call' => CallEventRender::class,
        'call_ytel' => CallEventRender::class,
        'customer' => CustomerEventRender::class,
        'fax' => FaxEventRender::class,
        'sms' => SmsEventRender::class,
        'task' => TaskEventRender::class
    ];
    private static $instances = [];
    private static $selfInstance;
    /**
     * @var \yii\web\View
     */
    private $widget;

    public static function getInstance(\yii\web\View $widget): self
    {
        if (!self::$selfInstance) {
            self::$selfInstance = new self($widget);
        }
        return self::$selfInstance;
    }

    public function __construct(\yii\web\View $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Check, if class <$model->object>EventRender exists and return it, otherwise return DefaultEventReader
     * @param History $model
     * @return BaseEventRender
     */
    public function getEventRender(History $model): BaseEventRender
    {
        $objectName = $model->object;
        $className = self::$classes[$objectName] ?? self::$classes['default'];
        if (isset(self::$instances[$className])) {
            return self::$instances[$className];
        }
        $instance = new $className($this->widget);
        if (!$instance instanceof BaseEventRender) {
            throw new \LogicException("$className shoud be instance of BaseEventRender");
        }
        return self::$instances[$className] = $instance;
    }
}