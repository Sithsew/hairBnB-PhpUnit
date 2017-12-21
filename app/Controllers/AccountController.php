<?php
/**
 * Created by PhpStorm.
 * User: sithara_s
 * Date: 11/8/2017
 * Time: 12:44 PM
 */

namespace App\Controllers;


class AccountController
{
    protected $error=[];
    protected $test_input;
    protected $path;
    protected $data;

    public function view($name, $data=[])
    {
        $this->data = $data;

        $this->path = "app/views/{$name}.view.php";
    }

    public function getView()
    {
        return[
            'view' => $this->path,
            'data' => $this->data,
        ];
    }

    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function signUp($firstName, $lastName, $email, $password, $salonAcc, $stylistAcc, $salonName, $stylistName, $emailPreference)
    {
        $userData=[];

        if (empty($firstName)) {
            $this->error['firstnameErr'] = 'First Name is required ';
        } else if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
            $this->error['firstnameErr']  = "Only letters and white space allowed";
        } else if ( strlen($firstName)> 64 ){
            $this->error['firstnameErr']  = "Your First Name is too long";
        } else {
            $userData['firstName']=$this->test_input($firstName);
        }


        if (empty($lastName)) {
            $this->error['lastnameErr'] = "Last Name is required ";
        } else if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
            $this->error['lastnameErr']  = "Only letters and white space allowed";
        } else if (strlen($lastName)>64){
            $this->error['lastnameErr']  = "Your Last Name is too long";
        } else {
            $userData['lastName']=$this->test_input($lastName);
        }


        if (empty($email)) {
            $this->error['emailErr'] = "Email is required ";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error['emailErr'] = "Invalid email format";
        } else {
            $emailExists = 0;
            if (empty($emailExists)){
                $userData['email']=$this->test_input($email);
            } else {
                $this->error['emailErr']="Email already exist.";
            }
        }


        if (empty($password)) {
            $this->error['passwrdErr'] = "Password is required ";
        } else if (strlen($password) <4 || strlen($password)>64 ){
            $this->error['passwrdErr']  = "Password must have atleast 4 characters and Not More than 64 characters";
        } else {
            $userData['password'] = md5($password);
        }


        if (empty($salonAcc) && empty($stylistAcc)){
            $this->error['acc_typeErr'] = "Account Type Required";
        } else if ( (!empty($salonAcc) && !empty($salonName)) && (!empty($stylistAcc) && !empty($stylistName)) ) {

            if (strlen($salonName) > 64) {
                $this->error['salonErr'] = "Your salon Business Name is too long";
            } else {
                $userData['salon'] = $this->test_input($salonName);
            }

            if (strlen($stylistName) > 64) {
                $this->error['stylistErr'] = "Your Business Name is too long";
            } else {
                $userData['stylist'] = $this->test_input($stylistName);
            }

            if (!empty($userData['salon']) && !empty($userData['stylist'])) {
                $userData['user_role'] = 3;
            }

        } else if ( !empty($salonAcc) && !empty($salonName)){
            if (strlen($salonName) > 64) {
                $this->error['salonErr'] = "Your salon Business Name is too long";
            } else {                    $userData['user_role'] = 1;
                $userData['user_role'] = 1;
                $userData['salon'] = $this->test_input($salonName);
            }
        } else if (!empty($stylistAcc) && !empty($stylistName)) {
            if (strlen($stylistName) > 64) {
                $this->error['stylistErr'] = "Your Business Name is too long";
            } else {
                $userData['user_role']=2;
                $userData['stylist']=$this->test_input($stylistName);
            }
        } else {
            $this->error['acc_typeErr'] = "Fill Your Business Name or Account Type Correctly";
        }

        if (!empty($emailPreference)){
            $userData['email_preference']=1;
        } else {
            $userData['email_preference']=0;
        }


        if (!empty($this->error)){
            $errors =$this->error;
            $this->view('signUpPage', compact('errors'));
        } else if (empty($this->error)) {
            $date = date('Y-m-d H:i:s');
            $userData['temp_password'] =  md5($userData['email']." ".$date);
            if (!empty($userData['firstName']) && !empty($userData['lastName']) && !empty($userData['email']) && !empty($userData['password']) &&!empty($userData['user_role']) && !empty($userData['temp_password']))
            {
                $this->setUser($userData);
                $this->view('welcome');
                $this->sendEmail($userData);

            }
        }
    }

    public function sendEmail($userData)
    {
        $url=$userData['temp_password'];
        $to = $userData['email'];
        $subject = "Activate Your Hair BnB Account";

        $message = "
            <html>
                <head>
                    <title>Hair BnB</title>
                </head>
                
                <body >
                        <div class='col-md-10' align='center' >
                       <div  style='padding:2%; background-color: plum; width: 300px' >
                         <h3 align='center' style='padding: 20px'> Welcome to Hair BnB</h3>
                        <div  style='margin-bottom: 40px'>
                            <p>Thanks for signing up with Hair BnB! </p>
                            <p>You must follow this link to activate your account:</p>
                            <a href='http://localhost:7777/activatingLogin?id=$url'>Click me, Please!</a>
                        </div>
                        
                        <div >
                            The Hair BnB Team<br>
                           <a href='hairBnB.com'>hairBnB.com</a>
                        </div>
                    </div>
                        </div>
                    
                    
                </body>
            </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//        mail($to,$subject,$message,$headers);
        $this->view('welcome');
    }

    protected $user=[];

    public function setUser($userData)
    {
        $this->user['id']=1;
        $this->user['firstName'] = $userData['firstName'];
        $this->user['lastName'] = $userData['lastName'];
        $this->user['email'] = $userData['email'];
        $this->user['password'] = $userData['password'];
        $this->user['user_role'] = $userData['user_role'];
        $this->user['temp_password'] = 'bff435cf2cfaf786871f54bdec2ea31a';
    }

    public function getUser()
    {
        return $this->user;
    }

    public function activateUser($email, $password, $uri)
    {
        $url = $uri;

        if (!empty($email)) {
            $loginEmail = $email;
        }
        if (!empty($password)) {
            $loginPassword =  md5($password);
        }

        $existUser = $this->checkPassHash($loginEmail , $url);

        if (!empty($existUser)){
            $user = $this->email_password($loginEmail, $loginPassword );
            if (!empty($user)) {
                $active =1;
//                $active = $this->response->activate($loginEmail);
                if ($active){
                    $details = $this->getUser();
//                    $details = $this->response->getUser($loginEmail,$password);
                    if ($details){
//                        session_start();
                        $role = $details['user_role'];
                        $id=$details['id'];
                       $firstname=$details['firstName'];
                        $lastname= $details['lastName'];

                        if ($role==='3'){
                            $_SESSION['user_role'] = $role;
                            $this->view('bothUsers');
                        }elseif ($role==='1'){
                            $_SESSION['user_role'] = $role;
                            $this->view('salon');
                        }elseif ($role==='2'){
                            $_SESSION['user_role'] = $role;
                            $this->view('stylist');
                        }

                    }else {
                        $errors="Incorrect Email or Password";
                        $this->view('activatedLogin',compact('errors') );
                    }
                }else {
                    $errors="Whoops!!! Please try again shortly";
                    $this->view('activatedLogin',compact('errors') );
                }
            }else {
                $errors="Incorrect Email or Password";
                $this->view('activatedLogin',compact('errors') );

            }
        } else {
            $errors="Sorry, we cant activate your account. Something went wrong!!!";
            $this->view('activatedLogin',compact('errors') );
        }

    }

    public function checkPassHash($loginEmail , $url)
    {
        $email = $loginEmail;
        $temp_pass = $url;
        if ($email === $this->user['email'] && $temp_pass===$this->user['temp_password']) {
            return true;
        } else {
            return false;
        }
    }


//    ************* check email and password in database********
    public function email_password($email, $password)
    {
        if ($email==='sewsith@gmail.com' && $password=== md5(1234)){
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password)
    {
        if (!empty($email)) {
            $loginEmail = $email;
        }
        if (!empty($password)) {
            $loginPassword =  md5($password);
        }

        $user = $this->email_password($loginEmail,$loginPassword );
        if (!empty($user)) {
//            **********get user details from database to get user_role****************
            $details = $this->getUser();
//                    $details = $this->response->getUser($loginEmail,$password);
            if ($details){
//                        session_start();
                $role = $details['user_role'];
                $id=$details['id'];
                $firstname=$details['firstName'];
                $lastname= $details['lastName'];

                if ($role==='3'){
                    $_SESSION['user_role'] = $role;
                    $this->view('bothUsers');
                }elseif ($role==='1'){
                    $_SESSION['user_role'] = $role;
                    $this->view('salon');
                }elseif ($role==='2'){
                    $_SESSION['user_role'] = $role;
                    $this->view('stylist');
                }

            }else {
                $errors="Incorrect Email or Password";
                $this->view('activatedLogin',compact('errors') );
            }
        }else {
            $errors="Incorrect Email or Password";
            $this->view('activatedLogin',compact('errors') );

        }

    }

}