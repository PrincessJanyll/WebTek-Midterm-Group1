<?php


global $tr;

require_once '_AutoLoadClassAjax.php';

$aResponse['error'] = false;
$aResponse['message'] = '';
if(StudyPressUserWP::isLoggedIn()) {


    if (isset($_POST['action'])) {
        if (htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'rating') {

            
            $lessonId = (int) (isset($_POST['idBox'])?$_POST['idBox']:0);

            $value    = (int) (isset($_POST['rate'])?$_POST['rate']:0);

            $userId   = (int) (isset($_POST['user'])?$_POST['user']:0);




            $managerRate = new RateQualityManager();

            $currentUser = new StudyPressUserWP();
            if (($value >= 1 && $value <= 5) && ($currentUser->id() === $userId)) {


                if ($rate = $managerRate->voteExist($lessonId, $userId)) {
                    $rate->setValue($value);
                    $rate->setDateRate(StudyPressDB::getCurrentDateTime());
                    $managerRate->update($rate->getId(), $rate);
                } else {
                    $managerRate->add(new RateQuality(array(
                            'value' => $value,
                            'userId' => $userId,
                            'activityId' => $lessonId,
                            'dateRate' => StudyPressDB::getCurrentDateTime()
                        )
                    ));
                }
                if ($managerRate->isError()) {
                    $success = false;
                } else {
                    
                    $success = true;
                   
                }
            } else {
                $success = false;
            }

            if ($success) {
                $aResponse['message'] = 'Your rate has been successfuly recorded. Thanks for your rate :)';

                $aResponse['server'] = $tr->__('Your rating has been recorded') . '<br />';
                
                echo json_encode($aResponse);
            } else {
                $aResponse['error'] = true;
                $aResponse['message'] = 'An error occured during the request. Please retry';

                $aResponse['server'] = '<strong>' . $tr->__('ERROR') . ' :</strong> ' . $tr->__('Try Again');
               
                echo json_encode($aResponse);
            }
        } else {
            $aResponse['error'] = true;
            $aResponse['message'] = '"action" post data not equal to \'rating\'';


            echo json_encode($aResponse);
        }
    } else {
        $aResponse['error'] = true;
        $aResponse['message'] = '$_POST[\'action\'] not found';


    }
}
else
{
    $aResponse['server'] = $tr->__('You must be logged in to rate') . '<br />';
    echo json_encode($aResponse);
}

