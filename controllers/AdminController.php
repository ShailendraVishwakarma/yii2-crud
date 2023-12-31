<?php

namespace app\controllers;
use yii\data\ActiveDataProvider;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
// use app\models\Emp_salarys as ModelsEmp_salarys;
// use app\models\Employees;
use yii\web\UploadedFile;
use app\models\Emp_salarys;
class AdminController extends Controller
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
     * Displays homepage.
     *
     * @return string
     */
    // public function actionIndex()
    // {
    //     // return $this->render('index');
        
    //     // get data all from database...................
    //     $emp_salarys = Emp_salarys::find()

    //     ->select(['emp_salarys.*', 'employees.mobile_no AS mobile_no','employees.fullname AS name']) // Replace with the actual columns you want to select
    //     ->from(['emp_salarys' => 'emp_salarys'])
    //     ->innerJoin('employees employees', 'emp_salarys.employee_id = employees.id');
    //     // ->where(['emp_salarys.mobile_no' => '12345']); // Optional: Add conditions
    
    //     // Execute the query and fetch the results
    //     $rows = $emp_salarys->all();

        
    //     return $this->render('home',['emp_salarys' => $rows]);
    // }

    public function actionIndex()
{
    $dataProvider = new ActiveDataProvider([
        'query' => Emp_salarys::find()->joinWith('employees'),
    ]);

    return $this->render('home', [
        'dataProvider' => $dataProvider,
    ]);
}


    // send data in database.....................

    public function actionCreate()
    {
        $emp_salary = new Emp_salarys();
        $formData = Yii::$app->request->post();
        if($emp_salary->load($formData)){

            if($emp_salary->save()){
                Yii::$app->getSession()->setFlash('message','Emp_salary Publish Successfully');
                return $this->redirect(['index']);
            }
            else{
                Yii::$app->getSession()->setFlash('message','failed');
            }
        }
        return $this->render('create',['emp_salary' => $emp_salary]);

    }


    // public function actionCreate()
    // {
    // $emp_salary = new Emp_salarys();
    // $formData = Yii::$app->request->post();

    // if ($emp_salary->load($formData)) {
    //     // Check if profile_photo was uploaded
    //     $emp_salary->profile_photo = UploadedFile::getInstance($emp_salary, 'profile_photo');
        
    //     if ($emp_salary->profile_photo) {
    //         $fileName = time() . '.' . $emp_salary->profile_photo->extension;
    //         $filePath = 'uploads/' . $fileName;
            
    //         // Save the uploaded file
    //         if ($emp_salary->profile_photo->saveAs($filePath)) {
    //             $emp_salary->profile_photo = $fileName;
    //         }
    //     }

    //     if ($emp_salary->save()) {
    //         Yii::$app->getSession()->setFlash('message', 'Employee Published Successfully');
    //         return $this->redirect(['index']);
    //     } else {
    //         Yii::$app->getSession()->setFlash('message', 'Failed to save employee');
    //     }
    // }

    // return $this->render('create', ['emp_salary' => $emp_salary]);
    // }

    // show the view page...................
    public function actionView($id)
    {
        // echo $id;
        $emp_salary=Emp_salarys::findOne($id);
        return $this->render('view',['emp_salary'=>$emp_salary]);
        
    }




    // update column...............
    public function actionUpdate($id)
    {
        // echo $id;
        $emp_salary=Emp_salarys::findOne($id);
        if($emp_salary->load(Yii::$app->request->post()) && $emp_salary->save()){
            Yii::$app->getSession()->setFlash('message','Emp_salary Updated Successfully');
            return $this->redirect(['index','id'=>$emp_salary->id]);
        }else{
            Yii::$app->getSession()->setFlash('message','failed');
        }
        
        return $this->render('update',['emp_salary'=>$emp_salary]);
        
    }




    // delete data.......................
    public function actionDelete($id)
    {
        // return $this->render('delete');
        $emp_salary = Emp_salarys::findOne($id)->delete();
        if($emp_salary){
            Yii::$app->getSession()->setFlash('message','Emp_salary Deleted Successfully');
            return $this->redirect(['index']);
        }
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
