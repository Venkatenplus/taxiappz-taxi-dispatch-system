<?php

namespace App\Jobs;

use App\Service\KreaitService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Models\taxi\Settings;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    /**
     * Title of the push notification
    */
    public $title;

     /**
     * Subtitle of the push notification
    */
    public $sub_title;
    
    /**
     * Message of the push notification
    */
    public $body;

    /**
     * Fcm token to which the push send
    */
    public $token;

    /**
     * Android or ios - to change payload
    */
    public $login;
    
    /**
     * Sound if needed
    */

    public $sound;

    

    public $notification_type;


    public function __construct(string $title,$sub_title = null,array $body,$token,string $login,$sound = null,$notification_type = null)
    {
        $this->title = $title;
        $this->sub_title = $sub_title;
        $this->body  = $body;
        $this->token = $token;
        $this->login = $login;
        $this->sound = $sound;
        $this->notification_type = $notification_type;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       // if (is_array($this->token)) {
            $deviceTokens = $this->token;
      //  }else{
          //  $deviceTokens = [$this->token];
      //  }

      $credentialsFilePath = env('FIREBASE_CREDENTIAL'); //replace this with your actual path and file name
      $client = new \Google_Client();
      $client->setAuthConfig($credentialsFilePath);
      $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
      $client->refreshTokenWithAssertion();
      $betoken = $client->getAccessToken();
      $fcm =  $betoken['access_token']; 

     // dd($fcm);
        
        try {
            $url = env('FCM_PUSH_MSG_URL');

            $image = null;
            if (array_key_exists('image',$this->body)) {
                $image = $this->body['image'];
            }
            $notify_type = "0";
            if($this->notification_type != null){
                $notify_type = "1";
            }
           
            if (strtolower($this->login) == 'android') {
                
                 $data = (object)[
                    "message" => (object)[
                        "token"=> $deviceTokens,
                        "data"=> (object)[
                        "body"=> json_encode($this->body),
                        "title"=> $this->title,
                        "image"=>  $image,
                         "notification_type"=> $notify_type, // 1 = General ; 0 = trip
                        ]
                    ]
                ];
            }
            else{

                if($this->sound == "1"){
                    $sounds = "CreateRequest.aiff";
                }
                else{
                    $sounds = "1";
                }

                $data = (object)[
                    "message" => (object)[
                       "token"=> $deviceTokens,
                        "notification"=> (object)[
                        "title"=> $this->title,
                        "body" => $this->sub_title,
                        ],
                        "apns"=> (object) [
                            'payload' => (object) [
                              'aps'=> (object)[
                                "sound"=> $sounds                              
                               ]
                             ]
                        ]
                    ]
                ];
            }

            $response = json_encode($data);
        
            $headers = [
                'Authorization:Bearer '. $fcm,
                'Content-Type: application/json',
            ];
        
            // CURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $response);

            $output = curl_exec($ch);
            if ($output === FALSE) {
                die('Curl error: ' . curl_error($ch));
            }        
            curl_close($ch);
            
           

            
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
    
    /**
     * Validate fcm token
     * TODO - need to check
     * 
     * @ref - https://firebase-php.readthedocs.io/en/stable/cloud-messaging.html#validating-registration-tokens
    */
    public function validateFcmToken($messaging,$tokens)
    {
        $result = $messaging->validateRegistrationTokens($tokens);

        if(count($result['invalid']) > 0) Log::error('invalid tokens ',$result['invalid']);
        if(count($result['unknown']) > 0) Log::error('unknown tokens ',$result['unknown']);

        return $result['valid'];
    }
}
