<?php

namespace notify;

use Yii;
use notify\helpers\MentionHelper;
use notify\models\UserMention;
use post\models\Post;
use user\models\User;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'notify\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @param Post $post
     * @return boolean
     */
    public function mentionHandler($post)
    {
        $usernames = MentionHelper::find($post->message);
        if (!empty($usernames)) {
            foreach ($usernames as $username) {
                $currentUser = Yii::$app->getUser()->getIdentity();
                if ($username == $currentUser->username) {
                    continue;
                }

                /** @var User $mentioned */
                $mentioned = User::findByUsername($username);
                if (!$mentioned instanceof User) {
                    continue;
                }

                $query = UserMention::find()
                    ->where([
                        'post_id' => $post->id,
                        'mention_user_id' => $mentioned->id,
                        'status' => UserMention::MENTION_SATUS_UNVIEWED,
                    ]);
                if ($query->exists()) {
                    continue;
                }

                $model = new UserMention();
                $model->user_id = $currentUser->id;
                $model->mention_user_id = $mentioned->id;
                $model->post_id = $post->id;
                $model->topic_id = $post->topic->id;
                $model->status = UserMention::MENTION_SATUS_UNVIEWED;

                if ($mentioned->notify_mention_web == 1) {
                    $model->save();
                }

                if ($mentioned->notify_mention_email == 1) {
                    Yii::$app->mailer->compose(['text' => 'mention'], [
                        'model' => $model,
                        'topic' => $post->topic,
                    ])
                    ->setFrom([Yii::$app->config->get('support_email') => Yii::$app->config->get('site_title')])
                    ->setTo([$model->mentionUser->email => $model->mentionUser->username])
                    ->setSubject('#' . $post->id . ' ' . $post->topic->subject)
                    ->send();
                }
            }
            return true;
        }
        return false;
    }
}
