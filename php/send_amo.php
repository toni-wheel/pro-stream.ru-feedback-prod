<?php
require_once realpath(dirname(__FILE__,4) . '/vendor/autoload.php');

	 $fileSet = realpath(dirname(__FILE__,4) . '/fateechev/set.json');
     $set = file_get_contents($fileSet);
     $set=json_decode($set,true);
	 
     $file = realpath(dirname(__FILE__,4) . '/fateechev/token.json');
     $current = file_get_contents($file);
     $current=json_decode($current,true);
     $token=$current['access_token'];
	 

// ----------------------------------------------------------------------------------------------------
              $amo = new \AmoCRM\Client($set['subdomain'], $token);
			  date_default_timezone_set($set['time_zone']);
			  
			  $admin ="";
			  $lead_id = 0;
			  $con_id = "";
			  $zal = '5';
			  $name = 'Александр';
			  $phone = '+79233576589';
			  $startDate = '2024-05-29';
			  $startTime = '13:00';
			  $time_min = 0;
			 
			  $endDate = '2024-05-29';
			  $endTime = '14:00';
			  $mainServiceString = "Аренда + съемка";
			  $addServiceString = 'Дополнительная камера';
			  $rentalHours ='01:00';
			  $tel = '+'.preg_replace("/[^0-9]/", '', $phone);
			  $parameters=[];			  
			  $parameters['query'] = $phone;
			  $parameters['limit'] = 1;	//
              $data_contacts = $amo->account->Rec('/api/v4/contacts',$parameters);
			  
			  if (isset($data_contacts['_embedded']['contacts'][0]['id'])){
			       $con_id = $data_contacts['_embedded']['contacts'][0]['id'];
			  }
			  
	 		//if($zal != "7"){$zal = $zal." зал";} else { $zal = "большой кустрим";}  
			
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
	        print_r($date_go);
			if($con_id != ""){					
                  $parameters=[];		  
                  $parameters[0]['responsible_user_id'] = 9804222;                                            	 
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
				  
				  
				  if ($addServiceString != ''){
				  $parameters[0]['custom_fields_values'][6]['field_id'] = 1018727;																															
			      $parameters[0]['custom_fields_values'][6]['values'] = $service1;
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
                  $parameters[0]['name'] = 'Бронь';                                                                                                        
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
				  
				  
				  if ($addServiceString != ''){
				  $parameters[0]['custom_fields_values'][6]['field_id'] = 1018727;																															
			      $parameters[0]['custom_fields_values'][6]['values'] = $service1;
				  }
			  
                  $parameters[0]['_embedded']['tags'][0]['name']='Бронь';
			  
			      $parameters[0]['_embedded']['contacts'][0]['custom_fields_values'][0]['field_id'] = 660589;                     
			      $parameters[0]['_embedded']['contacts'][0]['custom_fields_values'][0]['values'][0]['value'] = $tel;    		  
			  
			      $par=json_encode($parameters);
			  
			      $create_leads = $amo->account->RecPost('/api/v4/leads/complex',$par);
			      $lead_id = $create_leads[0]['id'];
			      
			}
			//print_r($lead_id);
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
