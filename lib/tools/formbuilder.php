<?php

require_once "validaforms.php";
//require_once "nocsrf.php";


class FormBuilder {

	var $NoCSRF = true;

	var $fields = array();

	var $btnlabel;

	var $formname;

	var $formintro;

	var $submitURL;

    var $Thanks;

	var $formchecked = false;

	var $submitvals = array();

	var $formerrors = array();

			

	function FormBuilder($formname,$btnlabel="send",$formintro=null,$usePseudoLegends=false,$showDividers=false,$submitURL=null){

		$this->btnlabel = $btnlabel;

		$this->formname = $formname;

		$this->formintro = $formintro;

		$this->usePseudoLegends = $usePseudoLegends;

		$this->showDividers = $showDividers;

		$this->submitURL = $submitURL;

		if ($this->submitURL==null){

			$this->submitURL=$_SERVER["REQUEST_URI"];

		}

		// create instance of valida forms (validation) class

		$this->vFs = new validaForms;

	}

/**

 *	checkSubmit

 *	===========

 *

 *	Checks if form has been completed correctly

 *

 *	@return bool

 *

 */	

	function checkSubmit(){

		$this->formchecked = true;

		if (isset($_POST['form_name'])&&$_POST['form_name']==$this->formname) {
            if($this->NoCSRF)
            {
                try
                {
                    NoCSRF::check( $this->formname, $_POST, true, 60*60, false );
            
                }
                catch ( Exception $e )
                {
    
    				array_push($this->formerrors,array('id'=>'none','errormsg'=> $e->getMessage()));
                    return false;
                }                
            }

			foreach ($this->fields as $field) {

				if (in_array($field['type'],array('fieldset','html')) == false){

					if (get_magic_quotes_gpc()) {

					    $_POST[$field['id']] = stripslashes($_POST[$field['id']]);

					}

					$errorFound = false;

					$required = (isset($field['required'])&&$field['required']==true) ? 1 : 0;

					$maxlength = (isset($field['maxlength'])) ? $field['maxlength'] : null;

					$minlength = (isset($field['minlength'])) ? $field['minlength'] : 0;

					$invalidvalue = (isset($field['invalidvalue'])) ? $field['invalidvalue'] : null;

					$headerinjectioncheck = (isset($field['headerinjectioncheck'])) ? $field['headerinjectioncheck'] : null;

					if (isset($field['validationtype'])){

						switch ($field['validationtype']){

							case "alpha":

								$validationtype=1;

								break;

							case "numeric":

								$validationtype=2;

								break;

							case "alphanumeric":

								$validationtype=3;

								break;

							case "email":

								$validationtype="email";

								break;

							default:

								$validationtype=null;

						}					

					} else {

						$validationtype=null;

					}

					// now perform the validation

					if ($field['type']=="textbox"||$field['type']=="textarea"||$field['type']=="password"){

						if ($validationtype!="email"){

							if ((!$this->vFs->textBox($field['label'],$_POST[$field['id']],$required,$validationtype,$minlength,$maxlength))){

								$errorFound = true;

							}

						} else {

							if ((!$this->vFs->emailCheck($field['label'],$_POST[$field['id']],$required))){

								$errorFound = true;

							}

						}

						

					}

					if ($field['type']=="file" || $field['type']=="imagefile"){

						if ((!$this->vFs->textBox($field['label'],$_POST[$field['id']],$required,$validationtype,$minlength,$maxlength))){

								$errorFound = true;

							}

					}

					if ($field['type']=="select"){

						if((!$this->vFs->selectBox($field['label'],$_POST[$field['id']],$invalidvalue))){

							$errorFound = true;

						}

					}

					if ($field['type']=="radio"&&$required==1){

						// in case no radio button was selected, set POST value to null

						if (!isset($_POST[$field['id']])){

							$_POST[$field['id']]=null;

						}

						if((!$this->vFs->radioButton($field['label'],$_POST[$field['id']]))){

							$errorFound = true;

						}

					}

					if ($field['type']=="checkbox"){

						// This will concatenate all checkboxes with the same name into a comma seperated list,

						// and copy the result into $_POST[$field['id']]

						$_POST[$field['id']]=null;

						if (!isset($_POST[$field['id'].'_arr'])){

							$_POST[$field['id'].'_arr']=null;

						}

						if ($required==1){

							if((!$this->vFs->checkBox($field['label'],$_POST[$field['id'].'_arr']))){

								$errorFound = true;

							} else {

								// loop through all checkboxes in this 'group' and concatenate their values

								foreach ($_POST[$field['id'].'_arr'] as $value){

									$_POST[$field['id']].=$value.',';

								}

								// tidy up: strip last comma

								$_POST[$field['id']]=substr($_POST[$field['id']],0,strlen($_POST[$field['id']])-1);

							}

						}

					}

					if ($errorFound){

						// Validation error! Push details of the error into our formerrors array for display to the user.

						array_push($this->formerrors,array('id'=>$field['id'],'errormsg'=>$this->vFs->erro));

					} else {

						// Form header injection validation

						if ($headerinjectioncheck=='full'){

							if ($this->checkHeaderInjection($_POST[$field['id']])){

								array_push($this->formerrors,array('id'=>$field['id'],'errormsg'=>'The \''.$field['label'].'\' field contains formatting that may cause errors processing this form. This may occur if you pasted text into the field from another application, or your text includes "bcc:" or "content-type:".'));

							}

						}

						if ($headerinjectioncheck=='light'){

							if ($this->checkHeaderInjectionLight($_POST[$field['id']])){

								array_push($this->formerrors,array('id'=>$field['id'],'errormsg'=>'The \''.$field['label'].'\' field contains formatting that may cause errors processing this form. This may occur if your text includes "bcc:" or "content-type:".'));

							}

						}

					}

				}

			}

			if (count($this->formerrors) > 0){

				// there was one or more errors detected by valida forms

				return false;

			}

			// if we got this far, form was successfully validated!

			return true;

		} else {

			// form hasn't been submitted

			return false;

		}

	}	

/**

 *	checkHeaderInjection

 *	====================

 *

 *	Protects form fields against Header Injection attacks

 *

 *	@return bool Returns true if a header injection attack was detected

 *

 */		

	function checkHeaderInjection($value){

		if (preg_match("/%0A/i",$value) || preg_match("/%0D/i",$value) || preg_match("/\r/i",$value) || preg_match("/\n/i",$value) || preg_match("/bcc:/i",$value) || preg_match("/content-type:/i",$value)){

			return true;

		} else {

			return false;

		}

	}

/**

 *	checkHeaderInjection

 *	====================

 *

 *	Lightweight header injection protection. For fields that are allowed linebreaks,

 *  but still contain telltale header injection strings.

 *

 *	@return bool Returns true if a header injection attack was detected

 *

 */		

	function checkHeaderInjectionLight($value){

		if (preg_match("/bcc:/i",$value) || preg_match("/content-type:/i",$value)){

			return true;

		} else {

			return false;

		}

	}	

/**

 *	addFieldSet

 *	===========

 *

 *	Adds a new HTML fieldset and legend to the form

 *

 *	@param string $legend A description for the fieldset, to be rendered in a <legend> tag

 *

 */		

	function addFieldSet($legend){

		$fieldset = array(

			'type' =>'fieldset',

			'legend' => $legend

		);

		array_push($this->fields,$fieldset);

	}

    

	function addCollapse($id,$legend){

		$fieldset = array(

			'id' => $id,

			'type' =>'collapse',

			'legend' => $legend

		);

		array_push($this->fields,$fieldset);

	}    

    

/**

 *	closeFieldSet

 *	=============

 *

 *	Closes an HTML fieldset

 *

 *

 */		

	function closeFieldSet(){

		$fieldset = array(

			'type' =>'closefieldset'

		);

		array_push($this->fields,$fieldset);

	}

/**

 *	addField

 *	========

 *

 *	Adds a new HTML form field to the form

 *

 *	@param array $field An array containing all the key/value pairs describing the field

 *

 */		

	function addField($field){

		array_push($this->fields,$field);

		//echo($this->fields[0]['type']);

	}

/**

 *	forceErrorMessage

 *	=================

 *

 *	Adds a new custom error message to the errors array.

 *  Useful for if you want to do custom form validation

 *  after FormBuilder has completed its own validation,

 *  and want to display an error message to the user.

 *

 *	@param string $fieldId The name of the form field that has the error message

 *	@param string $newError The error message to be displayed to the user

 *

 */		

	function forceErrorMessage($fieldId,$newError){

		array_push($this->formerrors,array('id'=>$fieldId,'errormsg'=>$newError));

	}

/**

 *	renderErrorMessage

 *	=================

 *

 *	Loops through all form validation errors and prints them to screen.

 *  If all regular validation errors are passed then any header injection

 *  errors will be displayed.

 *

 */		

	function renderErrorMessage(){

	   

		if (count($this->formerrors)>0){

		    ob_start();  

    			echo('<div class="fbformerrormessage">');

    			echo('<h2>'.tr('err_processing_form').'</h2>');

    			echo('<ul>');

    			foreach ($this->formerrors as $error) {

    				echo('<li>'.$error['errormsg'].'</li>');

    			}

    			echo('</ul>');

    			echo('</div>');

    		$html = ob_get_contents();

    		ob_end_clean();

    		return $html;             

		}

       

	}

/**

 *	renderThanks

 *	===========

 *

 *	Displays a Thank You message to the user upon successful

 *  processing of the form.

 *

 */		

	function renderThanks($heading=null,$message=null,$showLink=false,$btntarg=null,$btnlabel=null){

		if ($heading==null){

			$heading = tr('thank_you');

		}

		if ($message==null){

			$message = tr('form_processed');

		}

		if ($btnlabel==null){

			$btnlabel = tr('back');

		}

		if ($this->formSuccess()){

		    ob_start();  

    			echo('<div class="fbthanks">');

    			echo('<h2>'.$heading.'</h2>');

    			echo('<p>'.$message.'</p>');

    			if ($showLink){

    				if ($btntarg==null){

    					$btntarg = $_SERVER['PHP_SELF'];

    				}

    				echo('<p><a href="'.$btntarg.'">'.$btnlabel.'</a></p>');

    			}

    			echo('</div>');	

    		$html = ob_get_contents();

    		ob_end_clean();

    		$this->Thanks = $html;             

		}	

	}

/**

 *	isSubmitted

 *	===========

 *

 *	Checks if form has been submitted

 *

 *	@return bool

 *

 */	

	function isSubmitted(){

		if ($this->formchecked==false){

			$this->checkSubmit();

		}

		if (isset($_POST['form_name'])&&$_POST['form_name']==$this->formname) {

			return true;

		}

		return false;

	}

/**

 *	formSuccess

 *	===========

 *

 *	Checks to see if the form was subitted and successfully processed.

 *  Useful for selectively showing/hiding HTML content if form was successfully processed.

 *

 *  @return bool

 *

 *

 */		

	function formSuccess(){

		if ($this->formchecked==false){

			$this->checkSubmit();

		}

		if (isset($_POST['form_name'])

			&&$_POST['form_name']==$this->formname

			&&count($this->formerrors)==0) {

			return true;

		}

		return false;

	}



	function renderForm(){		
        if($this->NoCSRF)
        {
            $token = NoCSRF::generate( $this->formname );
        }

		$thisFormSubmitted = false;

		$fieldsetOpen = false;

		$collapseOpen = false;

		$dlOpen = false;

		$loopCount = 0;

		if (isset($_POST['form_name'])&&$_POST['form_name']==$this->formname) {

			$thisFormSubmitted = true;

		}

        

        ob_start();

        

        if(empty($this->Thanks) == false){

            echo $this->Thanks;

        }

        

        if($this->formSuccess() == false){

            echo $this->renderErrorMessage();

        }

		// Form introduction/instructions
        if(empty($this->formintro) == false)
        {
            echo sprintf('<p class="fbintro">%s, %s</p>',$this->formintro,ftr('form_intro',array('<span class="fbrequired">*</span>')));
        }
		

		// Open form

		echo('<form id="fbform" class="fbform form-horizontal" action="'.$this->submitURL.'" method="post" enctype="multipart/form-data">'."\n");

		// Loop through all form fields

		foreach ($this->fields as $field) {

			// Declare local variables	

			$fieldErrorFound = false;

			// Special case: Fieldset

			if ($field['type']=="fieldset"){

				if ($dlOpen){

					echo("\t".'</dl>'."\n");

					$dlOpen = true;

				}

				// In case another fieldset is open, close it

				if ($fieldsetOpen){

					echo('</fieldset>'."\n");

				}

				echo('<fieldset>'."\n");

				if ($this->usePseudoLegends==true){

					$legendEle = 'h3';

				} else {

					$legendEle = 'legend';

				}

				echo("\t".'<'.$legendEle.'>'.$field['legend'].'</'.$legendEle.'>'."\n");

				echo("\t".'<dl>'."\n");

				$fieldsetOpen = true;

				$dlOpen = true;

			// Special case: Close fieldset

			} else if ($field['type']=="collapse"){

				if ($collapseOpen){

					echo('</div>'."\n");

				}

				echo('<a href="#" data-toggle="collapse" data-target="#'.$field['id'].'" onclick="return false;">+ '.$field['legend'].'</a>'."\n");			 

				echo('<div id="'.$field['id'].'" class="collapse show-if-no-js">'."\n");			 

			} else if ($field['type']=="closefieldset"){

				if ($dlOpen){

					echo("\t".'</dl>'."\n");

					$dlOpen = true;

				}

				// In case another fieldset is open, close it

				if ($fieldsetOpen){

					echo('</fieldset>'."\n");

				}

				$fieldsetOpen = false;

				$dlOpen = false;

			// All regular form fields

			} else if ($field['type']!="hidden"){

				// This is the first field, and it's not a fieldset, so we need to open a <dl>

				if (!$dlOpen){

					echo("\t".'<dl>'."\n");

					$dlOpen = true;

				}

				// Field label

				echo("\t\t".'<div class="form-group">');



				echo('<label for="'.$field['id'].'" class="col-md-3 control-label');	



				if ($thisFormSubmitted){

					foreach ($this->formerrors as $error) {

						if ($error['id']==$field['id']){

							echo(' fbfielderror');

							$fieldErrorFound = true;

							break;

						}

					}

				}

				echo('">');

				echo($field['label']);

				if (isset($field['required'])&&$field['required']==true){

					if (!$fieldErrorFound){

						echo(' <span class="fbrequired">*</span>');

					} else {

						echo(' *');

					}

				}

				echo('</label>');	

                	

                echo('<div class="col-md-9">');



				switch ($field['type']){

					// Textfield

					case "textbox":

						echo("\t\t\t".'<input id="'.$field['id'].'" name="'.$field['id'].'" class="form-control" type="text" placeholder="'.$field['label'].'" ');

						if (isset($field['maxlength'])){

							echo('maxlength="'.$field['maxlength'].'" ');

						}

						echo('value="');

						if ($thisFormSubmitted){

							echo(@$_POST[$field['id']]);

						} else if(isset($field['defaultvalue'])) {

							echo($field['defaultvalue']);

						}

						echo('" />');

						break;

					// Password

					case "password":

						echo("\t\t\t".'<input id="'.$field['id'].'" name="'.$field['id'].'" class="form-control" type="password" placeholder="'.$field['label'].'" ');

						if (isset($field['maxlength'])){

							echo('maxlength="'.$field['maxlength'].'" ');

						}

						echo('value="');

						if ($thisFormSubmitted){

							echo(@$_POST[$field['id']]);

						}

						echo('" />');

						break;

					// File

					case "file":

                    case "imagefile":



                	   $has_value = (empty($field['defaultvalue']) == false) ? true : false;

                       $class = ($has_value == false) ? 'input-new' : '';



                            switch ($field['type']){ 

                            	case 'file':

                                    //echo sprintf('<div data-provides="fileupload" class="fileupload %s"><div class="input-append"><div class="uneditable-input span2"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">%s</span></div><span class="btn btn-file"><span class="fileupload-new">إختيار ملف</span><span class="fileupload-exists">تغيير</span><input name="%s" type="file" /></span></div></div>',$class,($has_value == true) ? $field['defaultvalue'] : '',$field['id'] );    

                            	break;

                            

                            	case 'imagefile':

                                    echo sprintf(

                                        '<div class="featured-image-input %s">

                                            <span class="featured-image">%s</span>

                                            <input type="hidden" name="%s" value="%s" />

                                            <button type="button" class="close cancel" role="button">&times;</button>

                                            <button type="button" class="btn btn-sm btn-default btn-block" role="button" data-toggle="modal" data-target="#media-tab">%s</button>

                                        </div>',

                                        $class,

                                        ($has_value == true) ? sprintf('<img class="img-thumbnail" src="%s" />',$field['defaultimage']) : '',

                                        $field['id'],

                                        ($has_value == true) ? $field['defaultvalue'] : 0,

                                        tr('select')

                                    );    

                            	break;

                            }



						break;

					// Textarea

					case "textarea":

						if (isset($field['rows'])) {

							$rows = $field['rows'];

						} else {

							$rows = "5";	

						}

						if (isset($field['cols'])) {

							$cols = $field['cols'];

						} else {

							$cols = "35";	

						}

                        if (isset($field['toolbar'])) {

                            echo('<p>');

                                echo '<button type="button" class="btn btn-xs btn-default" role="button" data-toggle="modal" data-target="#media-tab"><i class="fa fa-image"></i> '.tr('add_media').'</button>';

                            echo('</p>');

                        }                        

						echo("\t\t\t".'<textarea id="'.$field['id'].'" name="'.$field['id'].'" class="form-control" rows="'.$rows.'" cols="'.$cols.'">');

						if ($thisFormSubmitted){

							echo(@$_POST[$field['id']]);

						} else if(isset($field['defaultvalue'])) {

							echo($field['defaultvalue']);

						}

						echo('</textarea>');



						break;

					// Select

					case "select":

						echo("\t\t\t".'<select id="'.$field['id'].'" name="'.$field['id'].'" class="form-control">'."\n");

						for ($n=0; $n<count($field['optionlabels']);$n++) {

							echo("\t\t\t\t".'<option value="'.$field['optionvalues'][$n].'"');

							if ($thisFormSubmitted){

								if ($_POST[$field['id']]==$field['optionvalues'][$n]){

									echo(' selected="selected"');

								}

							} else if(isset($field['defaultvalue']) && $field['optionvalues'][$n]==$field['defaultvalue']) {

								echo(' selected="selected"');

							} else if(isset($field['disabledvalues']) && in_array($field['optionvalues'][$n],$field['disabledvalues'])) {

								echo(' disabled="disabled"');

							}

							echo('>'.$field['optionlabels'][$n].'</option>'."\n");

						}

						echo("\t\t\t".'</select>'."\n");

						break;

					// Radio buttons

					case "radio":
                        $class = (isset($field['inline'])) ? 'radio-inline' : 'radio';
                        
						for ($n=0; $n<count($field['radiolabels']);$n++) {

							echo("\t\t\t".'<div class="'.$class.'"><label>'."\n");

							echo("\t\t\t\t".'<input name="'.$field['id'].'" type="radio" value="'.$field['radiovalues'][$n].'"');

							if ($thisFormSubmitted){

								if (isset($_POST[$field['id']])&&$_POST[$field['id']]==$field['radiovalues'][$n]){

									echo(' checked="checked"');

								}

							} else if(isset($field['defaultvalue'])&&$field['radiovalues'][$n]==$field['defaultvalue']) {

								echo(' checked="checked"');

							}

							echo(' /> ');

							echo($field['radiolabels'][$n]."\n");

							echo("\t\t\t".'</label></div>'."\n");

						}

						break;

					// Checkboxes

					case "checkbox":
						for ($n=0; $n<count($field['checkboxlabels']);$n++) {

							echo("\t\t\t".'<div class="checkbox"><label>'."\n");

							echo("\t\t\t\t".'<input name="'.$field['id'].'_arr[]" class="fbcheckbox" type="checkbox" value="'.$field['checkboxvalues'][$n].'"');

							if ($thisFormSubmitted){

								if (isset($_POST[$field['id'].'_arr'])){

									foreach ($_POST[$field['id'].'_arr'] as $value){

										if ($value==$field['checkboxvalues'][$n]){

											echo(' checked="checked"');

										}	

									}	

								}

							} else if(isset($field['checkboxchecked'])&&$field['checkboxchecked'][$n]==true) {

								// if checkbox is supposed to be checked by default

								echo(' checked="checked"');

							}

							echo(' /> ');

							echo($field['checkboxlabels'][$n]."\n");

							echo("\t\t\t".'</label></div>'."\n");

						}

					break;

                        

                    case "html":

                        echo($field['html']."\n");

					break;

				}

                

				// Render Instructions

				if (isset($field['instructions'])){

					echo("\n\t\t\t".'<div class="help-block">'.$field['instructions'].'</div>');

				}

                

				echo("\n\t\t".'</div></div>'."\n");



				if ($this->showDividers==true){

					echo("\t\t".'<dd class="fbformdivider');

					if ($loopCount+1<count($this->fields)){

						if ($this->fields[$loopCount+1]['type']=="fieldset"||$this->fields[$loopCount+1]['type']=="closefieldset"){

							echo(' fblast');

						}

					}

					echo('">&nbsp;</dd>'."\n");

				}

			}

			$loopCount++;

		}

		echo("\n\t".'</dl>'."\n");

		if ($fieldsetOpen){

			echo('</fieldset>'."\n");

		}

		if ($collapseOpen){

			echo('</div>'."\n");

		}

		echo('<fieldset class="fbsubmit">'."\n");

		// All hidden variables

		foreach ($this->fields as $field) {

			if ($field['type']=="hidden"){

				echo('<input id="'.$field['id'].'" name="'.$field['id'].'" type="hidden" value="'.$field['value'].'" />'."\n");

			}

		}
		echo('<input name="form_name" type="hidden" value="'.$this->formname.'" />'."\n");
        
        if($this->NoCSRF)
        {
            echo('<input name="'.$this->formname.'" type="hidden" value="'.$token.'" />'."\n");
        }
		

		// Submit Button

		echo('<input id="submit" type="submit" name="submit" value="'.$this->btnlabel.'" class="btn btn-sm btn-default" />'."\n");

		echo('</fieldset>'."\n");

		// close form

		echo('</form>'."\n");

        

		$html = ob_get_contents();

		ob_end_clean();

		return $html;        		

	}	

}



?>