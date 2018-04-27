<?php


class validaforms
{
    public $campo;            // Nome do campo
    public $valor;            // Valor do campo
    public $valor2;        // Valor do campo de confirmacao de senha
    public $type;            // Tipo do campo alpha=1 numeric=2 alphanumeric=3
    public $tamanhoMin;    // Tamanho m�nimo do campo
    public $tamanhoMax;    // Tamanho m�ximo do campo
    public $requerido;        // Campo obrigat�rio ou nao -- obrigat�rio=1 ou 0 para n�o obrigat�rio
    public $notValor;        // Valor que nao pode estar selecionado no selectbox
    public $erro;            // Mensagem de erro que ser� retornada para o usu�rio
    public $relacao;        // Id do campo que tem relacionamento com outro campo. Ex: Outros
    public $campoRel;        // Nome do campo do relacionado com $relacao
    public $valorRel;        // Valor do campo do relacionado com $relacao
    public $language;        // Language file

/*
 *	getErrorMsgs
 *	============
 *
 *	This Method is used to return the error message in the specified language.
 *	Default language is english. Portuguese supported. Add another language if you want, let me know ;)
 *	This Method will be called when an error occurs.
 *	Most of the params below are set during the script, maybe you'll never use it directly.
 *
 *	Params:
 *			$error		=> It's the name of the error related in the language file;
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML checkbox;
 *			$tamanhoMin	=> This param tells de min length for the input, deafult is 0;
 *			$tamanhoMax	=> This param tells de max length for the input, default is null;
 */

    public function getErrorMsgs($error, $campo, $tamanhoMin, $tamanhoMax)
    {
        // error messages hardcoded by JN instead of included from external file
        $ERROR_MSG["textBox_Req"]            = ftr('form_textbox_req', array($campo));
        $ERROR_MSG["textBox_Len"]            = ftr('form_textbox_len', array($campo, "$tamanhoMin-$tamanhoMax"));
        $ERROR_MSG["textBox_LenMin"]        = ftr('form_textbox_lenmin', array($campo, $tamanhoMin));
        $ERROR_MSG["textBox_LenMax"]        = ftr('form_textbox_lenmax', array($campo, $tamanhoMax));
        $ERROR_MSG["selectBox_Not"]            = ftr('form_selectbox_not', array($campo));
        $ERROR_MSG["check_radioButton"]        = "";
        $ERROR_MSG["passwordCheck_Match"]    = "";
        $ERROR_MSG["dateCheck_Format"]        = "";
        $ERROR_MSG["emailCheck"]            = ftr('form_emailcheck', array($campo));
        $ERROR_MSG["lettersCheck"]            = ftr('form_letterscheck', array($campo));
        $ERROR_MSG["numbersCheck"]            = ftr('form_numberscheck', array($campo));
        $ERROR_MSG["numbers_lettersCheck"]  = ftr('form_numletcheck', array($campo));
        //if (!isset($this->language))
            //$this->language = "english";
        //require_once "langs/".$this->language.".lang.php";
        return $ERROR_MSG[$error];
    }

/*
 *	textBox
 *	=======
 *
 *	This Method is used to validate html forms type="text".
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML input
                           eg. <input name="test"> You should use $teste;
 *			$requerido	=> This param tells if your input is required or not:
 *								$requerido=1 (default)
 *								$requerido=0 for not required;
 *			$type		=> This param tells if your input must be alpha, numeric or alphanumeric:
 *								$type = null (default)
 *								$type = 1 for alpha
 *								$type = 2 for numeric
 *								$type = 3 for alphanumeric;
 *			$tamanhoMin	=> This param tells de min length for the input, deafult is 0;
 *			$tamanhoMax	=> This param tells de max length for the input, default is null;
 */

    public function textBox($campo, $valor, $requerido = 1, $type = null, $tamanhoMin = 0, $tamanhoMax = null)
    {
        if ($requerido) {
            if (!($valor)) {
                $this->erro = $this->getErrorMsgs("textBox_Req", $campo, null, null);

                return false;
            }
        }

        if ($tamanhoMax == null) {
            if (Webfairy::strlen($valor)<$tamanhoMin) {
                $this->erro = $this->getErrorMsgs("textBox_LenMin", $campo, $tamanhoMin, $tamanhoMax);

                return false;
            }
        } elseif (Webfairy::strlen($valor)<$tamanhoMin) {
            $this->erro = $this->getErrorMsgs("textBox_LenMin", $campo, $tamanhoMin, $tamanhoMax);

            return false;
        } elseif (Webfairy::strlen($valor)>$tamanhoMax) {
            $this->erro = $this->getErrorMsgs("textBox_LenMax", $campo, $tamanhoMin, $tamanhoMax);

            return false;
        }

        if ($type !== null) {
            return $this->typeCheck($campo, $valor, $type);
        }

        return true;
    }

/*
 *	selectBox
 *	=========
 *
 *	This Method is used to validate html forms <select>.
 *  It also contains a feature to validate the possibility of the "others" selection,
 *	in this case you should specify what input is related with the <select>. See below.
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML <select>
 *						   eg. for <select name="test"> You should use $test;
 *			$notValor	=> This param specify wich value your <select> must not have, default is null;
 *			$relacao	=> This param specify wich is the value that must implicity the fillment of another;
 *						   eg: If "other" is selected, the <input type=text name="others"> is required;
 *			$campoRel	=> It's the name that will appear in the error message of the related input in the situation above;
 *			$valorRel	=> It's the name of the HTML input of the related field above;
 *			$tamanhoMin	=> This param tells de min length for the input of the related field above, deafult is 0;
 *			$tamanhoMax	=> This param tells de max length for the input of the related field above, default is null;
 *			$type		=> This param tells if your input must be alpha, numeric or alphanumeric:
 *								$type = null (default)
 *								$type = 1 for alpha
 *								$type = 2 for numeric
 *								$type = 3 for alphanumeric;
 */

    public function selectBox($campo, $valor, $notValor = null, $relacao = null, $campoRel = null, $valorRel = null, $tamanhoMin = null, $tamanhoMax = null, $type = null)
    {
        if ($valor == $notValor) {
            $this->erro = $this->getErrorMsgs("selectBox_Not", $campo, null, null);

            return false;
        }
        if ($relacao) {
            if ($valor == $relacao) {
                return $this->textBox($campoRel, $valorRel, 1, $type, $tamanhoMin, $tamanhoMax);
            }
        }

        return true;
    }

/*
 *	radioButton
 *	===========
 *
 *	This Method is used to validate html forms type=radio.
 *  It also contains a feature to relate a input type=text with one of the selected radios.
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML input:
 *						   eg: for <input type="radio" name="test"> You should use $test;
 *			$relacao	=> This param specify wich is the value that must implicity the fillment of another;
 *						   eg: If "yes" is selected, the <input type=text name="specify"> is required;
 *			$campoRel	=> It's the name that will appear in the error message of the related input in the situation above;
 *			$valorRel	=> It's the name of the HTML input of the related field above;
 *			$tamanhoMin	=> This param tells de min length for the input of the related field above, deafult is 0;
 *			$tamanhoMax	=> This param tells de max length for the input of the related field above, default is null;
 *			$type		=> This param tells if your input must be alpha, numeric or alphanumeric:
 *								$type = null (default)
 *								$type = 1 for alpha
 *								$type = 2 for numeric
 *								$type = 3 for alphanumeric;
 */

    public function radioButton($campo, $valor, $relacao = null, $campoRel = null, $valorRel = null, $tamanhoMin = null, $tamanhoMax = null, $type = null)
    {
        if ($valor == null) {
            $this->erro = $this->getErrorMsgs("check_radioButton", $campo, null, null);

            return false;
        } else {
            if ($relacao) {
                if ($valor == $relacao) {
                    return $this->textBox($campoRel, $valorRel, 1, $type, $tamanhoMin, $tamanhoMax);
                }
            }
        }

        return true;
    }

/*
 *	passwordCheck
 *	=============
 *
 *	This Method is used to validate typed and re-typed passwords.
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML input type=password 1:
 *						   eg: for <input type="password" name="pass1"> You should use $pass1;
 *			$valor2		=> It's the name of your HTML input type=password 2:
 *						   eg: for <input type="password" name="pass2"> You should use $pass2;
 *			$tamanhoMin	=> This param tells de min length for the input of the related field above, deafult is 0;
 *			$tamanhoMax	=> This param tells de max length for the input of the related field above, default is null;
 *			$type		=> This param tells if your input must be alpha, numeric or alphanumeric:
 *								$type = null (default)
 *								$type = 1 for alpha
 *								$type = 2 for numeric
 *								$type = 3 for alphanumeric;
 */

    public function passwordCheck($campo, $valor, $valor2, $tamanhoMin, $tamanhoMax, $type = null)
    {
        if ($valor !== $valor2) {
            $this->erro = $this->getErrorMsgs("passwordCheck_Match", null, null, null);

            return false;
        } else {
            return $this->textBox($campo, $valor, 1, $type, $tamanhoMin, $tamanhoMax);
        }

        return true;
    }

/*
 *	dateCheck
 *	=========
 *
 *	This Method is used to validate date.
 *  This Method use another one called catchDate wich assumes the format dd/mm/yyyy,
 *	you may change to satisfy your country format.
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML input;
 *			$requerido	=> This param tells if your input is required or not:
 *								$requerido=1 (default)
 *								$requerido=0 for not required;
 */

    public function dateCheck($campo, $valor, $requerido = 1)
    {
        if ($requerido) {
            if (!$valor) {
                return $this->textBox($campo, $valor, 1, null, 0, 1000);
            } else {
                return $this->catchDate($campo, $valor);
            }
        } else {
            if (!$valor) {
                return true;
            } else {
                return $this->catchDate($campo, $valor);
            }
        }
    }

/*
 *	checkBox
 *	========
 *
 *	This Method is used to validate html type=checkbox.
 *	At least one of the checkboxes must be checked.
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML checkbox;
 */

    public function checkBox($campo, $valor)
    {
        if (!$valor[0]) {
            $this->erro = $this->getErrorMsgs("check_radioButton", $campo, null, null);

            return false;
        } else {
            return true;
        }
    }

/*
 *	emailCheck
 *	==========
 *
 *	This Method is used to validate html an e-mail type in a type=text.
 *
 *	Params:
 *			$campo		=> It's the name that will appear in the error message;
 *			$valor		=> It's the name of your HTML checkbox;
 *			$requerido	=> This param tells if your input is required or not:
 *								$requerido=1 (default)
 *								$requerido=0 for not required;
 */

    public function emailCheck($campo, $valor, $requerido)
    {
        if ($requerido) {
            if (!$valor) {
                return $this->textBox($campo, $valor);
            } else {
                return $this->catchEmail($campo, $valor);
            }
        } else {
            if (!$valor) {
                return true;
            } else {
                return $this->catchEmail($campo, $valor);
            }
        }
    }

/*
 *	The Method below are used during the script. (Secondary Methods)
 *	Probably you'll never access them directly.
 */

    public function catchEmail($campo, $valor)
    {
        if (!preg_match("/^([a-z0-9\\_\\.\\-]+)@([a-z0-9\\_\\.\\-]+)\\.([a-z]{2,4})$/i", $valor)) {
            $this->erro = $this->getErrorMsgs("emailCheck", $campo, null, null);

            return false;
        }

        return true;
    }

/*
 * catchDate
 * =========
 *
 * You can change this Method to best fit your demands.
 * Here I use the date format dd/mm/yyyy.
 * You should change the first line to:
 *			list($Month, $Day, $Year) = explode("-",$valor);
 * to attend the format mm-dd-yyyy.
 */

    public function catchDate($campo, $valor)
    {
        list($Day, $Month, $Year) = explode("/", $valor);
        if (strlen($Year) == 4) {
            if (!checkdate($Month, $Day, $Year)) {
                $this->erro = $this->getErrorMsgs("dateCheck_Format", $campo, null, null);

                return false;
            }
        } else {
            $this->erro = $this->getErrorMsgs("dateCheck_Format", $campo, null, null);

            return false;
        }

        return true;
    }

    public function typeCheck($campo, $valor, $type)
    {
        switch ($type) {
            case 1:
               return $this->catchLetters($campo, $valor);
               break;
            case 2:
               return $this->catchNumbers($campo, $valor);
               break;
            case 3:
               return $this->catchLettersAndNumbers($campo, $valor);
               break;
        }
    }

    public function catchLetters($campo, $valor)
    {
        if (!preg_match('/^[a-zA-Z����������������������[:space:]]+$/', $valor)) {
            $this->erro = $this->getErrorMsgs("lettersCheck", $campo, null, null);

            return false;
        }

        return true;
    }

    public function catchNumbers($campo, $valor)
    {
        if (!is_numeric($valor)) {
            $this->erro = $this->getErrorMsgs("numbersCheck", $campo, null, null);

            return false;
        }

        return true;
    }

    public function catchLettersAndNumbers($campo, $valor)
    {
        if (!preg_match('/^[a-zA-Z0-9_����������������������[:space:]]+$/', $valor)) {
            $this->erro = $this->getErrorMsgs("numbers_lettersCheck", $campo, null, null);

            return false;
        }

        return true;
    }
}
