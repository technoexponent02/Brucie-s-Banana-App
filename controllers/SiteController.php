<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PacketForm;
class SiteController extends Controller
{
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
        //return $this->render('index');

        $model = new PacketForm();
        $combinations_of_packets = [];
        $no_of_bananas = 0;

        if (!empty(Yii::$app->request->post())){
            $model->attributes = Yii::$app->request->post('PacketForm');
            if ($model->validate()){
                $no_of_bananas = $model->attributes['quantity'];
                $combinations_of_packets = $this->countNumberOfPackets($no_of_bananas);
            }
        }

        return $this->render('packets', [
            'model' => $model,
            'combinations_of_packets' => $combinations_of_packets,
            'no_of_bananas' => $no_of_bananas
        ]);        
    }

    public function countNumberOfPackets($no_of_bananas)
    {
        $packets = [250,500,1000,2000,5000];
            
        $quantity = 0;
        $no_of_packets = 0;
        $combinations_of_packets = [];
    
        foreach ($packets as $key => $val){
            if ($val == $no_of_bananas){
                $quantity = $no_of_bananas;
                $no_of_packets = 1;
                $combinations_of_packets["'".$quantity."'"] = $no_of_packets;
                break;
            }
            else if ($key == 0 && $no_of_bananas < $val){
                $quantity = $val;
                $no_of_packets = 1;
                $combinations_of_packets["'".$quantity."'"] = $no_of_packets;
                break;            
            }
            else if ($key == count($packets) - 1){
                $combinations_of_packets["'".$val."'"] = floor($no_of_bananas/$val);
                $rem = $no_of_bananas%$val;
                if ($rem != 0){
                    $combinations_of_packets2 = $this->countNumberOfPackets($rem);
                    $combinations_of_packets = array_merge($combinations_of_packets,$combinations_of_packets2); 
                }
                break;
            }
            else if ($no_of_bananas > $val && $no_of_bananas < $packets[$key+1]){
                $combinations_of_packets["'".$val."'"] = floor($no_of_bananas/$val);
                $rem = $no_of_bananas%$val;
                if ($rem != 0){
                    $combinations_of_packets2 = $this->countNumberOfPackets($rem);
                    if (array_key_exists("'".$packets[$key]."'", $combinations_of_packets) && array_key_exists("'".$packets[$key]."'", $combinations_of_packets2)){
                        unset($combinations_of_packets["'".$packets[$key]."'"]);
                        $combinations_of_packets2 = [];
                        $combinations_of_packets2["'".$packets[$key+1]."'"] = 1;
                    }
                    $combinations_of_packets = array_merge($combinations_of_packets,$combinations_of_packets2); 
                }
                break;
            }
        }
        
        return $combinations_of_packets;
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
}
