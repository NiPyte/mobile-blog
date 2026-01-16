<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Article;
use app\models\Topic;
use yii\data\Pagination;
use app\models\Comment;
use app\models\SignupForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage with article list and search functionality.
     *
     * @return string
     */
    public function actionIndex()
    {
        // 1. Create a basic query to select all articles
        $query = Article::find();

        // 2. Get the search parameter from the URL
        $search = Yii::$app->request->get('search');

        if ($search) {
            // Filter: Title OR Description OR Tag matches the search term
            $query->andWhere(['or',
                ['like', 'title', $search],
                ['like', 'description', $search],
                ['like', 'tag', $search] // Fulfills "Search by tags" requirement
            ]);
        }

        // 3. Order by date (newest first)
        $query->orderBy(['date' => SORT_DESC]);

        // 4. Setup Pagination (e.g., 3 articles per page)
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 3]);

        // 5. Get articles for the current page
        $articles = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        // 6. Get topics for Sidebar
        $topics = Topic::find()->all();

        // 7. Render the view
        return $this->render('index', [
            'articles' => $articles,
            'pages' => $pages,
            'topics' => $topics,
            'search' => $search, // Pass search term back to view to keep it in input
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);

        if (!$article) {
            throw new \yii\web\NotFoundHttpException("Article not found");
        }

        // 1. Prepare Comment Model
        $comment = new Comment();

        // 2. Handle Comment Submission
        if ($comment->load(Yii::$app->request->post())) {
            if (!Yii::$app->user->isGuest) {
                $comment->user_id = Yii::$app->user->id; // Current User
                $comment->article_id = $article->id;     // Current Article
                $comment->date = date('Y-m-d');          // Current Date
                $comment->delete_status = 0;             // Default status

                if ($comment->save()) {
                    Yii::$app->session->setFlash('success', "Comment added!");
                    // Refresh page to prevent duplicate submission on F5
                    return $this->refresh();
                }
            }
        }

        // 3. Increment views
        $article->updateCounters(['viewed' => 1]);

        // 4. Get data for view
        $topics = Topic::find()->all();
        $comments = $article->comments; // Get existing comments

        return $this->render('view', [
            'article' => $article,
            'topics' => $topics,
            'comment' => $comment, // Pass the form model
            'comments' => $comments, // Pass existing comments
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays articles from a specific category (Topic).
     */
    public function actionTopic($id)
    {
        $topic = Topic::findOne($id);

        if (!$topic) {
            throw new \yii\web\NotFoundHttpException("Category not found");
        }

        $query = Article::find()->where(['topic_id' => $id])->orderBy(['date' => SORT_DESC]);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 3]);
        $articles = $query->offset($pages->offset)->limit($pages->limit)->all();

        $topics = Topic::find()->all();

        return $this->render('index', [
            'articles' => $articles,
            'pages' => $pages,
            'topics' => $topics,
            'currentTopic' => $topic,
        ]);
    }

    /**
     * Displays articles by tag.
     */
    /**
     * Displays articles by tag.
     */
    public function actionTag($tag)
    {
        $query = Article::find()->where(['like', 'tag', $tag])->orderBy(['date' => SORT_DESC]);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 3]);
        $articles = $query->offset($pages->offset)->limit($pages->limit)->all();

        $topics = Topic::find()->all();

        return $this->render('index', [
            'articles' => $articles,
            'pages' => $pages,
            'topics' => $topics,
            'currentTopic' => (object)['name' => 'Tag: ' . $tag], // Fake object for title
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
