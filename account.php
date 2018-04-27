<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */
    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start();

    $Webfairy->require_auth();

    if (($row = $Webfairy->db->users[$Webfairy->Userid()]) == false) {
        $Webfairy->logout();
        $Webfairy->go_to();
    }

    $DMsg = '';

    if((int) $row['status'] == 2){
        $DMsg = $Webfairy->alert_html(tr('complete_profile'),'warning');
    }

    if(isset($_GET['code'])){
        switch ($_GET['code']){ 
        	case 'DETAILS_DONE':
                $DMsg = $Webfairy->alert_html(tr('form_processed'),'success');
        	break;
        }        
    }

    $attrs = array(
        'firstName' => '',
        'lastName' => '',
        'displayName' => 'username',
        'description' => '',
        'webSiteURL' => '',
        'photoType' => 'email',
        'coverFile' => 0
    );

    foreach ($Webfairy->db->userattrs('user_id',$Webfairy->Userid()) as $attr) {
    	$attrs[$attr['attr_key']] = $attr['attr_value'];
    }

    $Webfairy->loadClass('formbuilder', 'tools');

    $DForm = new FormBuilder('DForm', tr('save'), tr('my_details'), true);

    $DForm->addField(array(
            'id' => 'username',
            'type' => 'textbox',
            'label' => tr('username'),
            'required' => true,
            'defaultvalue' => $row['username'],
        )
    );

    $DForm->addField(array(
            'id' => 'firstName',
            'type' => 'textbox',
            'label' => tr('firstname'),
            'defaultvalue' => $attrs['firstName'],
        )
    );
    
    $DForm->addField(array(
            'id' => 'lastName',
            'type' => 'textbox',
            'label' => tr('lastname'),
            'defaultvalue' => $attrs['lastName'],
        )
    );

    $name_options = array();

    if(empty($row['username']) == false){
        $name_options['username'] = $row['username'];
    }
    
    if(empty($attrs['firstName']) == false && empty($attrs['lastName']) == false){
        $name_options['firstlast'] = $attrs['firstName'].' '.$attrs['lastName'];
        $name_options['lastfirst'] = $attrs['lastName'].' '.$attrs['firstName'];
    }

    $DForm->addField(array(
            'id' => 'displayName',
            'type' => 'select',
            'label' => tr('displayname'),
            'optionlabels' => array_values($name_options),
            'optionvalues' => array_keys($name_options),
            'defaultvalue' => $attrs['displayName'],
        )
    );

    $DForm->addField(array(
            'id' => 'email',
            'type' => 'textbox',
            'label' => tr('email'),
            'required' => true,
            'validationtype' => 'email',
            'defaultvalue' => $row['email'],
        )
    );

    $photo_options = array();
    
    if(empty($row['email']) == false){
        $photo_options['email'] = Webfairy::image_object(Webfairy::gravatar($row['email'],'32')).' '.tr('photo_using_email');
    }

    foreach ($Webfairy->EnabledLoginProviders() as $provider) {
    	if(empty($attrs[$provider.'_photoURL']) == false){
    	   $photo_options[$provider] = Webfairy::image_object($attrs[$provider.'_photoURL']).' '.ftr('photo_using_provider_x',array(tr($provider)));
    	}
    }

    if(count($photo_options) > 0){
        $DForm->addField(array(
                'id' => 'photoType',
                'type' => 'radio',
                'label' => tr('userphoto'),
                'radiolabels' => array_values($photo_options),
                'radiovalues' => array_keys($photo_options),
                'required' => true,
                'defaultvalue' => $attrs['photoType'],
            )
        );        
    }


    $DForm->addField(array(
            'id' => 'coverFile',
            'type' => 'imagefile',
            'label' => tr('cover_file'),
            'defaultvalue' => $attrs['coverFile'],
            'defaultimage' => ($cover_file = $Webfairy->db->files[$attrs['coverFile']]) ? $Webfairy->_uploaded('resized', 'thumb_'.$cover_file['file_physical_name']) : '',
        )
    );

    $Webfairy->media_tab_loader(
        array(
            'group' => 3,
            'buttons' => array(
                array(
                    'class' => 'set-featured-image',
                    'text' => tr('set_cover_image'),
                    'attrs' => array(),
                ),
            ),
        )
    );

    $DForm->addField(array(
            'id' => 'webSiteURL',
            'type' => 'textbox',
            'label' => tr('websiteurl'),
            'defaultvalue' => $attrs['webSiteURL'],
        )
    );
    
    $DForm->addField(array(
            'id' => 'description',
            'type' => 'textarea',
            'label' => tr('about'),
            'defaultvalue' => $attrs['description'],
        )
    );



    if ($DForm->formSuccess()) {
        $isvalid = true;

        $email = $Webfairy->plaintext($_POST['email']);
        $username = $Webfairy->plaintext($_POST['username']);


        $records = array(
            'username' => $username,
            'email' => $email,
            'status' => 1
        );
        
        $attr_records = array();

        foreach (array('firstName','lastName','displayName','photoType','coverFile','webSiteURL','description') as $key) {
        	$attr_records[$key] = $Webfairy->plaintext($_POST[$key]);
        }

        if ($Webfairy->db->users(
            "username = ? AND id != ?",
            $username,
            $Webfairy->Userid()
           )->fetch()) {
            $DForm->forceErrorMessage('username', ftr('err_already_exists', array($username)));
            $isvalid = false;
        }

        if ($Webfairy->db->users(
            "email = ? AND id != ?",
            $email,
            $Webfairy->Userid()
           )->fetch()) {
            $DForm->forceErrorMessage('email', ftr('err_already_exists', array($email)));
            $isvalid = false;
        }



        if ($isvalid == true) {
            $row->update($records);
            
            foreach (array_keys($attr_records) as $key) {
            	if($userattr = $Webfairy->db->userattrs('user_id',$Webfairy->Userid())->where('attr_key',$key)->fetch()){
            	   $userattr->update(array('attr_value' => $attr_records[$key]));
            	}else{
            	   $Webfairy->db->userattrs()->insert(
                        array(
                            'user_id' => $Webfairy->Userid(),
                            'attr_key' => $key,
                            'attr_value' => $attr_records[$key]
                        )
                   );
            	}
            }            
            
            foreach ($Webfairy->db->userattrs('user_id',$Webfairy->Userid())->where('attr_key',array_keys($attr_records)) as $attr) {
            	$attr['attr_value'] = $attr_records[$attr['attr_key']];
                $attr->update();
            }            
            
            $Webfairy->go_to('account.html?code=DETAILS_DONE');
        }
    }

    $Webfairy->smarty->assign('DForm', $DForm->renderForm());
    $Webfairy->smarty->assign('DMsg', $DMsg);

    $password_head = tr('change_password');
    $has_password = true;

    if(empty($row['password']) == true){
        $password_head = tr('set_password');
        $has_password = false;
    }

    $PForm = new FormBuilder('PForm', tr('save'), $password_head, true);

    if($has_password){
        $PForm->addField(array(
                'id' => 'old_password',
                'type' => 'password',
                'label' => tr('old_password'),
                'maxlength' => 50,
                'minlength' => 6,
                'required' => true,
                'headerinjectioncheck' => 'full',
            )
        );        
    }

    $PForm->addField(array(
            'id' => 'password',
            'type' => 'password',
            'label' => tr('password'),
            'maxlength' => 50,
            'minlength' => 6,
            'required' => true,
            'headerinjectioncheck' => 'full',
        )
    );

    $PForm->addField(array(
            'id' => 'confirm',
            'type' => 'password',
            'label' => tr('password_confirm'),
            'maxlength' => 50,
            'minlength' => 6,
            'required' => true,
            'headerinjectioncheck' => 'full',
        )
    );
    
    if ($PForm->formSuccess()) {
        $isvalid = true;
        $records = array();


        if($has_password){
            $old_password = $_POST['old_password'];
            $hash = hash('sha256', $row['salt'].hash('sha256', $old_password));
            
            if ($hash != $row['password']) {
                $isvalid = false;
                $PForm->forceErrorMessage('old_password', tr('incorrect_password'));
            }      
        }
        
        if (isset($_POST['password']) == true && empty($_POST['password']) == false ) {

            $password = $_POST['password'];
            $confirm = $_POST['confirm'];

            if ($password == $confirm) {
                $salt = $Webfairy->createSalt();
                $hash = hash('sha256', $password);

                $records['password'] = hash('sha256', $salt.$hash);
                $records['salt'] = $salt;
            } else {
                $isvalid = false;
                $PForm->forceErrorMessage('confirm', tr('password_not_match'));
            }
        } 
        
        if($isvalid){
            $row->update($records);
            $Webfairy->go_to('account.html?code=PASSWORD_DONE');
        }       
    }
    
    $Webfairy->smarty->assign('PForm', $PForm->renderForm());
    $Webfairy->smarty->assign('password_head', $password_head);
    
    $Webfairy->display(array('media-tab.tpl','account.tpl'), array(tr('my_account')));
