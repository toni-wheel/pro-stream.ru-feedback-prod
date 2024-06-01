<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Подключаем классы PHPMailer
require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require_once realpath(dirname(__FILE__,4) . '/vendor/autoload.php');

	 $fileSet = realpath(dirname(__FILE__,4) . '/fateechev/set.json');
     $set = file_get_contents($fileSet);
     $set=json_decode($set,true);
	 
     $file = realpath(dirname(__FILE__,4) .'/fateechev/token.json');
     $current = file_get_contents($file);
     $current=json_decode($current,true);
     $token=$current['access_token'];
	 


// Создаем экземпляр PHPMailer
$mail = new PHPMailer(true);

// Устанавливаем кодировку
$mail->CharSet = "UTF-8";

// Устанавливаем формат письма как HTML
$mail->IsHTML(true);

// Получаем данные из тела запроса
$postData = json_decode(file_get_contents("php://input"), true);

// Проверяем, получены ли данные
if ($postData) {
    // Извлекаем данные из массива
    $name = $postData['name'];
    $phone = $postData['phone'];
	$zal = $postData['zal'];
    $mainService = $postData['mainService'];
    $addService = $postData['addService'];
    $addService = $postData['addService'][0];
    $startTime = $postData['startTime'];
    $startDate = $postData['startDate'];
    $endTime = $postData['endTime'];
    $endDate = $postData['endDate'];
    $freeTime = $postData['freeTime'];
    $rentalHours = $postData['rentalHours'];
}

// Проверяем, получены ли данные и существует ли ключ mainService
if ($postData && isset($postData['mainService'])) {
  // Массив с правильными названиями основных услуг
  $mainServices = [
      "rent" => "Аренда",
      "rent-shooting" => "Аренда + съемка",
      "rent-shooting-broadcast" => "Аренда + съемка + трансляция"
  ];

  // Создаем переменную для хранения правильного названия основной услуги
  $mainServiceString = '';

  // Проверяем, существует ли ключ mainService в массиве $mainServices
  if (array_key_exists($postData['mainService'], $mainServices)) {
      // Если существует, присваиваем соответствующее название переменной $mainServiceString
      $mainServiceString = $mainServices[$postData['mainService']];
  }
} else {
  // Если ключ mainService не существует, устанавливаем его в пустую строку
  $mainServiceString = '';
}


// Проверяем, получены ли данные и существует ли ключ addService
if ($postData && isset($postData['addService'])) {
  // Массив с правильными названиями дополнительных услуг
  $additionalServices = [
      "flipchart" => "Флипчарт",
      "make-up-table" => "Гримерный стол",
      "teleprompter" => "Телесуфлер",
      "parking-space" => "Парковка",
      "plasma-panel" => "Плазменная панель",
      "add-tablet" => "Дополнительный планшет",
      "add-laptop" => "Дополнительный ноутбук",
      "add-microphone" => "Дополнительный микрофон",
      "add-camera" => "Дополнительная камера",
      "add-videographer" => "Дополнительный видеооператор",
      "add-engineer" => "Дополнительный инженер"
  ];

  // Создаем строку с правильными названиями дополнительных услуг
  $addServiceString = '';
  foreach ($postData['addService'] as $service) {
      if (array_key_exists($service, $additionalServices)) {
          $addServiceString .= $additionalServices[$service] . ',';
      }
  }
  // Удаляем последнюю запятую и пробел
  $addServiceString = rtrim($addServiceString, ',');

} else {
  // Если ключ addService не существует, устанавливаем его в пустую строку
  $addServiceString = '';
}

// Загружаем шаблон электронного письма
$email_template = "template_mail.html";
$body = file_get_contents($email_template); // Сохраняем данные в $body

// Заменяем заполнители в шаблоне на фактические данные
$body = str_replace('%zal%', $zal, $body);
$body = str_replace('%name%', $name, $body);
$body = str_replace('%phone%', $phone, $body);
$body = str_replace('%mainService%', $mainServiceString, $body);
$body = str_replace('%addService%', $addServiceString, $body);
$body = str_replace('%startTime%', $startTime, $body);
$body = str_replace('%startDate%', $startDate, $body);
$body = str_replace('%endTime%', $endTime, $body);
$body = str_replace('%endDate%', $endDate, $body);
$body = str_replace('%freeTime%', $freeTime, $body);
$body = str_replace('%rentalHours%', $rentalHours, $body);

// Указываем адрес электронной почты отправителя
$mail->setFrom("info@pro-stream.ru");

// Устанавливаем адресата и отправителя письма
$mail->addAddress("mover.lebedev@yandex.ru");
$mail->addAddress("fateechev@ya.ru");


// Устанавливаем тему письма
$mail->Subject = "Заявка с формы бронирования pro-stream.ru";

// Устанавливаем содержимое письма в формате HTML
$mail->MsgHTML($body);

// Пытаемся отправить письмо
if (!$mail->send()) {
    $message = "Ошибка отправки";
} else {
    $message = "Данные отправлены!";
}

// Формируем ответ в формате JSON
$response = ["message" => $message];

// Устанавливаем заголовок для ответа
header('Content-type: application/json');

// Выводим ответ в формате JSON
echo json_encode($response);
// ----------------------------------------------------------------------------------------------------
              $amo = new \AmoCRM\Client($set['subdomain'], $token);
			  date_default_timezone_set($set['time_zone']); 
			  
			  $lead_id = 0;
			  $tel = '+'.preg_replace("/[^0-9]/", '', $phone);
			  $con_id ="";
			  $parameters=[];			  
			  $parameters['query'] = $tel;
			  $parameters['limit'] = 1;	//
              $admin ="";
			  $time_min = 0;
			  
			  $data_contacts=$amo->account->Rec('/api/v4/contacts',$parameters);
			
			if (isset($data_contacts['_embedded']['contacts'][0]['id'])){
			       $con_id = $data_contacts['_embedded']['contacts'][0]['id'];
			  }
			$service1 = []; 
			if ($addServiceString != ''){
			$addServiceArray = explode(',',$addServiceString);
			foreach($addServiceArray as $s){
				                   if($s == 'Дополнительная камера') {$time_min = 30;}
					               $t = array('value' =>$s);  
					               array_push($service1,$t);	                           
                    }   
			  }
		   switch ($mainServiceString) {					             
                               case "Аренда":
								$time_min = $time_min + 0;
                                break;
					           case "Аренда + съемка":							
								$time_min = $time_min + 30;	
                                break;
                               case "Аренда + съемка + трансляция":
								$time_min = $time_min + 60;	
                                break;
                              }  
			switch ($zal) {					             
                               case "1":
								$admin = 'Евгений +7-926-510-50-60;fateechev@gmail.com;1';
                                break;
					           case "2":							
								$admin = 'Максим +7-968-991-21-35;maxbalta24@gmail.com;2';	
                                break;
                               case "3":
								$admin = 'Федор +7-929-503-52-75;fmalinin97@gmail.com;3';	
                                break;
								case "4":
								$admin = 'Олег +7-999-861-77-80;captain27satan@gmail.com;4';	
                                break;
								case "5":
								$admin = 'Артур +7-985-000-69-46;diordiev02@mail.ru;5';	
                                break;
								case "6":
								$admin = 'Алексей_Барабанов +7 985 439-69-18;Alexeybarabanov21@gmail.com;6';	
                                break;
                              }   
		    $date_beg = strtotime($startDate.' '.$startTime);
			$date_end = strtotime($endDate.' '.$endTime);
			$date_go = $date_beg-$time_min*60;
			$count_min = round(($date_end - $date_beg)/60,0);
			
			if($con_id != ""){
                 //Создание сделки	  
			      $parameters=[];		  
                  $parameters[0]['responsible_user_id'] = 9804222;
				  $parameters[0]['name'] = 'Предварительная бронь'; 
                  $parameters[0]['status_id'] = 50204956;                                                   
			      $parameters[0]['_embedded']['tags'][0]['name']='Бронь';
 		
			      $parameters[0]['custom_fields_values'][0]['field_id'] = 971011;																															
			      $parameters[0]['custom_fields_values'][0]['values'][0]['value'] = $zal." зал";
				
				  $parameters[0]['custom_fields_values'][1]['field_id'] = 971007;	
			      $parameters[0]['custom_fields_values'][1]['values'][0]['value'] = $date_go;
				  $parameters[0]['custom_fields_values'][2]['field_id'] = 970899;																															
			      $parameters[0]['custom_fields_values'][2]['values'][0]['value'] = $date_beg;
				  $parameters[0]['custom_fields_values'][3]['field_id'] = 971009;																															
			      $parameters[0]['custom_fields_values'][3]['values'][0]['value'] = $date_end;
				  $parameters[0]['custom_fields_values'][4]['field_id'] = 1018725;																															
			      $parameters[0]['custom_fields_values'][4]['values'][0]['value'] = $mainServiceString;
				  $parameters[0]['custom_fields_values'][5]['field_id'] = 982014;																															
			      $parameters[0]['custom_fields_values'][5]['values'][0]['value'] = $admin;
				  $parameters[0]['custom_fields_values'][6]['field_id'] = 1018729;																															
			      $parameters[0]['custom_fields_values'][6]['values'][0]['value'] = $count_min;
				  
				  
				  if ($addServiceString != ''){
				  $parameters[0]['custom_fields_values'][7]['field_id'] = 1018727;																															
			      $parameters[0]['custom_fields_values'][7]['values'] = $service1;
				  }

		      
			      $par=json_encode($parameters);			  
			      $create_leads = $amo->account->RecPost('/api/v4/leads',$par);
			      $lead_id = $create_leads['_embedded']['leads'][0]['id'];
	       
			      sleep(1);
                  $link = $amo->links;
                  $link['from'] = 'leads';
                  $link['from_id'] = $lead_id;
                  $link['to'] = 'contacts';
                  $link['to_id'] = $con_id;
				  $link->apiLink();
			}else{
                 //Создание сделки	 и контакта			
                  $parameters=[];
                  $parameters[0]['name'] = 'Предварительная бронь';                                                                                                        
                  $parameters[0]['responsible_user_id'] = 9804222; 
                  $parameters[0]['status_id'] = 50204956;                                                     
	              $parameters[0]['_embedded']['contacts'][0]['name'] = $name;                     
			      $parameters[0]['_embedded']['contacts'][0]['responsible_user_id'] = 9804222;               			  
		          $parameters[0]['custom_fields_values'][0]['field_id'] = 971011;																															
			      $parameters[0]['custom_fields_values'][0]['values'][0]['value'] = $zal." зал";

				  $parameters[0]['custom_fields_values'][1]['field_id'] = 971007;	
			      $parameters[0]['custom_fields_values'][1]['values'][0]['value'] = $date_go;
				  $parameters[0]['custom_fields_values'][2]['field_id'] = 970899;
				  $parameters[0]['custom_fields_values'][2]['values'][0]['value'] = $date_beg;
				  $parameters[0]['custom_fields_values'][3]['field_id'] = 971009;				 
			      $parameters[0]['custom_fields_values'][3]['values'][0]['value'] = $date_end;
				  $parameters[0]['custom_fields_values'][4]['field_id'] = 1018725;																															
			      $parameters[0]['custom_fields_values'][4]['values'][0]['value'] = $mainServiceString;
				  $parameters[0]['custom_fields_values'][5]['field_id'] = 982014;																															
			      $parameters[0]['custom_fields_values'][5]['values'][0]['value'] = $admin;
				  $parameters[0]['custom_fields_values'][6]['field_id'] = 1018729;																															
			      $parameters[0]['custom_fields_values'][6]['values'][0]['value'] = $count_min;
				  
				  
				  if ($addServiceString != ''){
				  $parameters[0]['custom_fields_values'][7]['field_id'] = 1018727;																															
			      $parameters[0]['custom_fields_values'][7]['values'] = $service1;
				  }
			  
                  $parameters[0]['_embedded']['tags'][0]['name']='Бронь';
			  
			      $parameters[0]['_embedded']['contacts'][0]['custom_fields_values'][0]['field_id'] = 660589;                     
			      $parameters[0]['_embedded']['contacts'][0]['custom_fields_values'][0]['values'][0]['value'] = $tel;    		  
			  
			      $par=json_encode($parameters);
			  
			      $create_leads = $amo->account->RecPost('/api/v4/leads/complex',$par);
				  $lead_id = $create_leads[0]['id'];
				  
				  
			}
			 sleep(2);
			 
		   	   if($lead_id != 0){
 			    if( $curl = curl_init() ) {
                                    curl_setopt($curl, CURLOPT_URL, 'http://lsoft.space/fateechev/cal_book.php?id='.$lead_id);
                                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                                    curl_exec($curl);      
                                    curl_close($curl);
                                   }
			   }
			
?>
