<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $date
 * @property string|null $image
 * @property string|null $tag
 * @property int|null $viewed
 * @property int|null $topic_id
 * @property int|null $user_id
 *
 * @property Comment[] $comments
 * @property Topic $topic
 * @property User $user
 */
class Article extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['viewed', 'topic_id', 'user_id'], 'integer'],
            [['title', 'image', 'tag'], 'string', 'max' => 255],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'date' => 'Date',
            'image' => 'Image',
            'tag' => 'Tag',
            'viewed' => 'Viewed',
            'topic_id' => 'Topic ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Get all comments for this article.
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['article_id' => 'id'])->where(['parent_id' => null]);
    }

    /**
     * Gets query for [[Topic]].
     *
     * @return \yii\db\ActiveQuery
     */
    /**
     * Format the date for display.
     * @return string
     */
    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->date);
    }

    // Check if Gii generated these relations. If not, add them too:
    public function getTopic()
    {
        return $this->hasOne(Topic::class, ['id' => 'topic_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            if($this->image instanceof UploadedFile) {
                $path = 'uploads/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $filename = strtolower(md5(uniqid($this->image->baseName))) . '.' . $this->image->extension;

                $this->image->saveAs($path . $filename);

                $this->image = $filename;
            }
            return true;
        } else {
            return false;
        }
    }

    public function getImage()
    {
        return ($this->image) ? '/uploads/' . $this->image : '/no-image.png';
    }

    public function deleteImage()
    {
        if ($this->image && file_exists('uploads/' . $this->image)) {
            unlink('uploads/' . $this->image);
        }
    }
}
