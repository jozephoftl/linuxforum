<?php

namespace app\models\forms;

use app\models\Post;
use app\models\Topic;
use Yii;

/**
 * Class TopicForm
 */
class PostForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $message;
    public $post;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'trim'],
            ['message', 'required', 'message' => Yii::t('app/form', 'Required message')],
            ['message', 'string', 'min' => 6, 'tooShort' => Yii::t('app/form', 'String short topic message')],
            ['message', 'string', 'max' => 65534, 'tooLong' => Yii::t('app/form', 'String long topic message')],
        ];
    }

    /**
     * @param Topic $topic
     * @return boolean
     */
    public function create($topic)
    {
        if ($this->validate()) {
            $user = Yii::$app->getUser()->getIdentity();

            $post = new Post();
            $post->poster = $user->username;
            $post->poster_id = $user->id;
            $post->poster_ip = Yii::$app->getRequest()->getUserIP();
            $post->poster_email = $user->email;
            $post->message = $this->message;
            $post->posted = time();
            $post->topic_id = $topic->id;
            $post->save();
            $this->post = $post;

            $user->num_posts += 1;
            $user->save();

            $topic->num_replies += 1;
            $topic->last_poster = $user->username;
            $topic->last_post = time();
            $topic->last_post_id = $post->id;
            $topic->save();

            $forum = $topic->forum;
            $forum->num_posts += 1;
            $forum->last_post = time();
            $forum->last_post_id = $post->id;
            $forum->last_poster = $user->username;
            $forum->save();

            return true;
        }

        return false;
    }
}