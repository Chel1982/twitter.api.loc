<?php

namespace app\controllers;

use app\models\UserSearch;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        if (\Yii::$app->request->post('UserSearch')){
            $search = \Yii::$app->request->post('UserSearch');
            Yii::$app->session['usersearch'] = $search['usersearch'];

            $this->actionAuthTwitter();

            $modelSearch = new UserSearch();
            return $this->render('index', [
                'modelSearch' => $modelSearch
            ]);
        }
        $modelSearch = new UserSearch();
        return $this->render('index', [
            'modelSearch' => $modelSearch
            ]);
    }

    public function actionAuthTwitter()
    {
        $twitter = Yii::$app->twitter->getTwitter();
        $request_token = $twitter->getRequestToken();

        //set some session info
        Yii::$app->session['oauth_token'] = $token = $request_token['oauth_token'];
        Yii::$app->session['oauth_token_secret'] = $request_token['oauth_token_secret'];

        if ($twitter->http_code == 200){
            //get twitter connect url
            $url = $twitter->getAuthorizeURL($token);
            //send them
            return $this->redirect($url);
        } else {
            //error here
            return $this->redirect(Url::home());
        }
    }

    public function actionTwitterCallBack()
    {
        /* If the oauth_token is old redirect to the connect page. */
        if (isset($_REQUEST['oauth_token']) && Yii::$app->session['oauth_token'] !== $_REQUEST['oauth_token']) {
            Yii::$app->session['oauth_status'] = 'oldtoken';
        }

        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $twitter = Yii::$app->twitter->getTwitterTokened(Yii::$app->session['oauth_token'], Yii::$app->session['oauth_token_secret']);

        /* Request access tokens from twitter */
        $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        Yii::$app->session['access_token'] = $access_token;

        /* Remove no longer needed request tokens */
        unset(Yii::$app->session['oauth_token']);
        unset(Yii::$app->session['oauth_token_secret']);

        if (200 == $twitter->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            Yii::$app->session['status'] = 'verified';

            //get an access twitter object
            $twitter = Yii::$app->twitter->getTwitterTokened($access_token['oauth_token'],$access_token['oauth_token_secret']);

            if (isset(Yii::$app->session['usersearch'])){

                $usersearch = Yii::$app->session['usersearch'];

                $twusers= $twitter->get('users/search', ['q' => ". $usersearch ."]);

                unset(Yii::$app->session['usersearch']);

                return $this->render('search-user', [
                    'twusers' => $twusers,
                ]);
            }

            if(isset(Yii::$app->session['user_id'])){

                $id = Yii::$app->session['user_id'];
                $userAdd= $twitter->post('friendships/create', ['user_id' =>  $id ]);

                unset(Yii::$app->session['user_id']);

                $modelSearch = new UserSearch();

                return $this->render('index', [
                    'userAdd' => $userAdd,
                    'modelSearch' => $modelSearch
                ]);
            }

            if(isset(Yii::$app->session['delete_user'])){

                $id = Yii::$app->session['delete_user'];
                $userDelete = $twitter->post('friendships/destroy', ['user_id' =>  $id ]);

                unset(Yii::$app->session['delete_user']);

                $modelSearch = new UserSearch();

                return $this->render('index', [
                    'userDelete' => $userDelete,
                    'modelSearch' => $modelSearch
                ]);
            }

            if(isset(Yii::$app->session['list'])){

                $usersList = $twitter->get('friends/list');

                unset(Yii::$app->session['list']);

                return $this->render('list-friends', [
                    'usersList' => $usersList
                ]);
            }

            if(isset(Yii::$app->session['get_twit'])){

                $id = Yii::$app->session['get_twit'];
                $twit = $twitter->get('statuses/user_timeline', ['user_id' => $id, 'count' => 1, 'tweet_mode' => 'extended']);

                unset(Yii::$app->session['get_twit']);

                return $this->render('twit', [
                    'twit' => $twit
                ]);
            }

            return $this->render('index');

        } else {
            /* Save HTTP status for error dialog on connnect page.*/
            //header('Location: /clearsessions.php');
            return $this->redirect(Url::home());
        }
    }

    public function actionAddUser($user_id)
    {
        Yii::$app->session['user_id'] = $user_id;

        $this->actionAuthTwitter();
    }

    public function actionListFriends()
    {
        Yii::$app->session['list'] = 1;

        $this->actionAuthTwitter();
    }

    public function actionDeleteUser($idUser)
    {
        Yii::$app->session['delete_user'] = $idUser;

        $this->actionAuthTwitter();
    }

    public function actionGetTwit($idUser)
    {
        Yii::$app->session['get_twit'] = $idUser;

        $this->actionAuthTwitter();
    }
}
