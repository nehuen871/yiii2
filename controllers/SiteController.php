<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\View;

class SiteController extends Controller
{
    //public $layout = 'newlayout';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    public function actionMaintenance() {
        echo "<h1>Maintenance</h1>";
    }
    public function actionRoutes() {
        return $this->render('routes');
    }
    public function actionTestResponse() {
        //throw new \yii\web\GoneHttpException;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
           'id' => '1',
           'name' => 'Ivan',
           'age' => 24,
           'country' => 'Poland',
           'city' => 'Warsaw'
        ];
    }
    //La funcion tiene que tener en el nombre response para poder identificar que es una respuesta
    public function actionTestDownloadResponse() {
        return \Yii::$app->response->sendFile('favicon.ico');
    }
    public function actionTestGet() {
        $req = Yii::$app->request;
        if ($req->isAjax) {
           echo "the request is AJAX";
        }
        if ($req->isGet) {
           echo "the request is GET";
        }
        if ($req->isPost) {
           echo "the request is POST";
        }
        if ($req->isPut) {
           echo "the request is PUT";
        }
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
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
    public function actionContact() {
        $model = new ContactForm();
        /** cambia el esenario para el modelo contacform */
        $model->scenario = ContactForm::SCENARIO_EMAIL_FROM_USER;
        //$model->scenario = ContactForm::SCENARIO_EMAIL_FROM_GUEST;
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params ['adminEmail'])) {
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
        //return $this->render('about');
        //envia datos a la view
        \Yii::$app->view->on(View::EVENT_BEGIN_BODY, function () {
            echo date('m.d.Y H:i:s');
         });
        $email = "admin@support.com";
        $phone = "+78007898100";
        return $this->render('about',[
           'email' => $email,
           'phone' => $phone
        ]);
    }
    //Envia a la view el mesaje por default que le pasas por un request
    public function actionSpeak($message = "default message") { 
        return $this->render("speak",['message' => $message]); 
     }
     /** muestra el datos del modelo al llamar a la funcion */
     public function actionShowContactModel() {
        $mContactForm = new \app\models\ContactForm();
        $mContactForm->name = "contactForm";
        $mContactForm->email = "user@gmail.com";
        $mContactForm->subject = "subject";
        $mContactForm->body = "body";
        /**muestra los atributos del formulario */
        //var_dump($mContactForm->attributes);
        /** muestra el formulario en formato json */
        return \yii\helpers\Json::encode($mContactForm);
     } 

    public function actionTestwidget() { 
        return $this->render('testwidget'); 
    }
}
