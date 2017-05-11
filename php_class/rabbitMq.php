<?php
/**
 * Created by PhpStorm.
 * User: ZhangBingShuai
 * Date: 2017/05/09
 * Time: 上午 10:34
 */
namespace console\controllers;
use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;

class RabbitmqController extends Controller
{
    public static $host ;
    public static $port ;
    public static $user ;
    public static $password;
    public static $vhost ;

    public $exchange    = 'router';
    public $queue       = 'msgs';
    public $consumerTag = 'consumer';


    public function init()
    {
        parent::init();
        self::$host     = \Yii::$app->params['push_rabbitmq']['host'];
        self::$port     = \Yii::$app->params['push_rabbitmq']['port'];
        self::$user     = \Yii::$app->params['push_rabbitmq']['user'];
        self::$password = \Yii::$app->params['push_rabbitmq']['password'];
        self::$vhost    = \Yii::$app->params['push_rabbitmq']['vhost'];
    }

    /*
     * 功能:消费rabbitMq中的消息
     */
    public function actionConsumer(){

    //$exchange = 'router';
    //$queue = '';
    //$consumerTag = 'hello-exchange3';

//        $exchange = 'exchange_name';
//        $queue = 'queue_name';
//        $consumerTag = 'zbs_test_rabbit';
    $connection = new AMQPStreamConnection(self::$host, self::$port, self::$user, self::$password, self::$vhost);
    //$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
    $channel = $connection->channel();
    /*
        The following code is the same both in the consumer and the producer.
        In this way we are sure we always have a queue to consume from and an
            exchange where to publish messages.
    */
    /*
        name: $queue
        passive: false
        durable: true // the queue will survive server restarts
        exclusive: false // the queue can be accessed in other channels
        auto_delete: false //the queue won't be deleted once the channel is closed.
    */
    $channel->queue_declare($this->queue, false, true, false, false);
    /*
        name: $exchange
        type: direct
        passive: false
        durable: true // the exchange will survive server restarts
        auto_delete: false //the exchange won't be deleted once the channel is closed.
    */
    $channel->exchange_declare($this->exchange, 'direct', false, true, false);
    $channel->queue_bind($this->queue, $this->exchange);

    $res = $channel->basic_consume($this->queue, $this->consumerTag, false, false, false, false, ['console\controllers\RabbitmqController','process_message']);
//var_dump($res);die;
//$count  = count($channel->callbacks);
//        var_dump($count);die;
    while (count($channel->callbacks)) {
        $channel->wait();
        //$this->process_message($channel);
    }
}

    /**
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     */
    public static function process_message($message)
    {
        echo "\n--------\n";
        echo "Time is:".date("Y-m-d H:i:s")."\r\n";
        echo $input = $message->body;
        $input_r = json_decode($input);
        echo "\n--------\n";
        self::do_work($input_r);
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }

    }

    function shutdown($channel, $connection)
    {
        $channel->close();
        $connection->close();
    }

    public static function do_work($input_r) {
        switch($input_r->type) {
            case 1:
                //....
            default:
                break;
        }
    }

 

}