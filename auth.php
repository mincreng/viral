<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    if ($Webfairy->isLoggedIn() == true) {
        $Webfairy->return_to();
    }

    if(isset($_GET['endpoint']) == true)
    {
        require_once( dirname(__FILE__).'/lib/Hybrid/Auth.php' );
        require_once( dirname(__FILE__).'/lib/Hybrid/Endpoint.php' ); 
        
        Hybrid_Endpoint::process();
        exit;    
    }

    $auth_config = array(
		"base_url" => $Webfairy->absolute_url($Webfairy->url('auth.html?endpoint=true')), 
		"providers" => array (),
		"debug_mode" => false,
		"debug_file" => "",
	);
        
    foreach ($Webfairy->EnabledLoginProviders() as $provider) {
    	$auth_config['providers'][$provider]['enabled'] = true;
        $id_key = (in_array($provider,array('Google','Facebook','Live')) == true) ? 'id' : 'key';
    	$auth_config['providers'][$provider]['keys'][$id_key] = $Webfairy->getOption($provider.'_key');
    	$auth_config['providers'][$provider]['keys']['secret'] = $Webfairy->getOption($provider.'_secret');
        if($provider == 'Facebook')
        {
            $auth_config['providers'][$provider]['trustForwarded'] = true;
        }
    }    

	$provider_name = $_REQUEST["provider"];
    
    try
    {
        $Webfairy->loadClass('Auth','Hybrid');

        $hybridauth = new Hybrid_Auth( $auth_config );

        $adapter = $hybridauth->authenticate( $provider_name );

        $user_profile = $adapter->getUserProfile();

    }
    catch( Exception $e )
    {
        $Webfairy->go_to("login.html?code=HYBRID_ERR&err=".$e->getCode());
    }
    
    if($user_provider = $Webfairy->db->userattrs('attr_key',$provider_name.'_id')->where('attr_value',$user_profile->identifier)->fetch())
    {
        $Webfairy->validateUser($user_provider['user_id'],true);    
    }
    else
    {
        if(isset($user_profile->email) && ($user = $Webfairy->db->users('email',$user_profile->email)->fetch()))
        {
           
            $attrs = array(
                $provider_name.'_id' => $user_profile->identifier,
                $provider_name.'_profileURL' => $user_profile->profileURL,
                $provider_name.'_photoURL' => $user_profile->photoURL
            );
                
            foreach ($attrs as $k => $v) {
                $Webfairy->db->userattrs()->insert(
                    array(
                        'user_id' => $user['id'],
                        'attr_key' => $k,
                        'attr_value' => $v
                    )
                );	
            }            
            
            $Webfairy->validateUser($user['id'],true);            
        }
        else
        {
            
            if((boolean) $Webfairy->getOption('registration',true) == false)
            {
                $Webfairy->redirect(tr('registration_closed'),$Webfairy->return_to('return',true));
            }
            
            $user_records = array(
                'created' => new NotORM_Literal("CURRENT_TIMESTAMP()")
            );
            
            if(isset($user_profile->displayName))
            {
                $user_records['username'] = $user_profile->displayName;
            }else{
                $user_records['username'] = $user_profile->firstName.' '.$user_profile->lastName;
            }
            
            
            if(isset($user_profile->email))
            {
                $user_records['email'] = $user_profile->email;
            }
            else
            {
                $user_records['status'] = 2;
            }
    
            if($user = $Webfairy->db->users()->insert($user_records))
            {
                $fields = array(
                    'identifier',
                    'profileURL',
                    'webSiteURL',
                    'photoURL',
                    'displayName',
                    'description',
                    'firstName',
                    'lastName',
                    'gender',
                    'language',
                    'age',
                    'birthDay',
                    'birthMonth',
                    'birthYear',
                    'emailVerified',
                    'phone',
                    'address',
                    'country',
                    'region',
                    'city',
                    'zip'
                );
                
                foreach ($fields as $field) {
                    if(isset($user_profile->$field) && empty($user_profile->$field) == false){
                        switch ($field){ 
                        	case 'identifier':
                                $field_name = $provider_name.'_id';
                        	break;
                        
                        	case 'profileURL':
                                $field_name = $provider_name.'_profileURL';
                        	break;
                        
                        	case 'photoURL':
                                $field_name = $provider_name.'_photoURL';
                        	break;
                        
                        	default :
                                $field_name = $field;
                        }         
                        switch ($field){ 
                        	case 'displayName':
                                $field_value = 'username';
                        	break;

                        	default :
                                $field_value = $user_profile->$field;
                        }                                       
                        $Webfairy->db->userattrs()->insert(
                            array(
                                'user_id' => $user['id'],
                                'attr_key' => $field_name,
                                'attr_value' => $field_value
                            )
                        );                    
                    }	
                }
                
                $Webfairy->validateUser($user['id'],true);
            }            
        }        
        
    }

    if ($Webfairy->isLoggedIn() == true) {
        $Webfairy->return_to();
    }    
            
?>