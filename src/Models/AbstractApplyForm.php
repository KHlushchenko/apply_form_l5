<?php
namespace Vis\ApplyForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use \Exception;
use \Request;
use \Validator;

use Vis\MailTemplates\MailT;

use Vis\ApplyForm\Helpers\InputCleaner;

/**
 * Class AbstractApplyForm
 * @package Vis\ApplyForm\Models
 */
abstract class AbstractApplyForm extends Model
{
    /** Defines table of ApplyForm
     * @var
     */
    protected $table;

    /** Defines guarded fields for mass assignment
     * @var array
     */
    protected $guarded = [];

    /** Defines Vis\ApplyForm\Helpers\InputCleaner class
     * @var
     */
    private $inputCleaner;

    /** Defines Vis\ApplyForm\Models\ApplyFormSettingEmail class
     * @var
     *
     */
    private $settingEmail;

    /** Defines Vis\ApplyForm\Models\ApplyFormSettingMessage class
     * @var
     */
    private $settingMessage;

    /** Defines raw inputData
     * @var array
     */
    private $inputData = [];

    /** Defines validation rules
     * Should be overwritten in implementations
     * @var array
     */
    protected $validationRules = [];

    /** Defines fileFieldName from Input
     * Optional
     * Should be overwritten in implementations
     * @var string
     */
    protected $fileFieldName = '';

    /** Defines fileStorageFolder where file will be stored
     * Optional
     * Should be overwritten in implementations
     */
    protected $fileStorageFolder = 'storage/apply_form_files/';

    /** Defines mailTemplate that will be used with Vis\MailTemplates\MailT class
     * Optional
     * Should be overwritten in implementations
     */
    protected $mailTemplate = '';

    /** Defines mailAddressSlug for setting in Vis\ApplyForm\Models\ApplyFormSettingEmail class
     * Optional
     * Should be overwritten in implementations
     */
    protected $mailAddressSlug = '';

    /** Defines return message
     * @var array
     */
    private $message = [
        'title'         => '',
        'description'   => '',
    ];

    /** Defines messageSlug for setting in Vis\ApplyForm\Models\ApplyFormSettingMessage class
     * Should be overwritten in implementations
     */
    protected $messageSlug = 'uspeh-sohraneniya';

    /** Initializes Vis\ApplyForm\Helpers\InputCleaner object
     * @return InputCleaner
     */
    final protected function inputCleaner(): InputCleaner
    {
        if (!$this->inputCleaner) {
            $this->inputCleaner = new InputCleaner();
        }

        return $this->inputCleaner;
    }

    /** Initializes Vis\ApplyForm\Models\ApplyFormSettingEmail object
     * @return ApplyFormSettingEmail
     */
    final protected function settingEmail(): ApplyFormSettingEmail
    {
        if (!$this->settingEmail) {
            $this->settingEmail = new ApplyFormSettingEmail();
        }

        return $this->settingEmail;
    }

    /** Initializes Vis\ApplyForm\Models\ApplyFormSettingMessage object
     * @return ApplyFormSettingMessage
     */
    final protected function settingMessage(): ApplyFormSettingMessage
    {
        if (!$this->settingMessage) {
            $this->settingMessage = new ApplyFormSettingMessage();
        }

        return $this->settingMessage;
    }

    /** Sets input raw inputData
     * @param array $inputData
     * @return $this
     */
    final public function setInputData(array $inputData)
    {
        $this->inputData = $inputData;

        return $this;
    }

    /** Gets raw InputData
     * @return array
     */
    private function getInputData(): array
	{
		return $this->inputData;
	}

    /** Gets validationRules
     * @return array
     */
    private function getValidationRules(): array
    {
        return $this->validationRules;
    }

    /** Gets fileFieldName
     * @return string
     */
    private function getFileFieldName(): string
    {
        return $this->fileFieldName;
    }

    /** Gets fileStorageFolder
     * @return string
     */
    private function getFileStorageFolder(): string
    {
        return $this->fileStorageFolder;
    }

    /** Gets mailTemplate
     * @return string
     */
    private function getMailTemplate(): string
	{
		return $this->mailTemplate;
	}

    /** Gets mailAddressSlug
     * @return string
     */
    private function getMailAddressSlug(): string
	{
		return $this->mailAddressSlug;
	}

    /** Gets messageSlug
     * @return string
     */
    private function getMessageSlug(): string
    {
        return $this->messageSlug;
    }

    /** Sets message
     * @param array $message
     */
    private function setMessage(array $message)
    {
        $this->message = $message;
    }

    /** Gets Message
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /** Validates Google ReCaptcha
     * @return bool
     */
    private function validateCaptcha(): bool
    {
        if(!config('apply_form.apply_form.grecaptcha.enabled')){
            return true;
        }

        if (!isset($this->getInputData()['grecaptcha_response'])) {
            $this->setMessage($this->settingMessage()->get('oshibka-kapchi'));
            return false;
        }

        $params = [
            'secret'   => config('apply_form.apply_form.grecaptcha.secret_key'),
            'response' => $this->getInputData()['grecaptcha_response'],
            'remoteip' => Request::getClientIp()
        ];

        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);

        $response = json_decode(file_get_contents($url));
        if ($response->success == false) {
            $this->setMessage($this->settingMessage()->get('oshibka-kapchi'));
            return false;
        }

        unset($this->getInputData()['grecaptcha_response']);

        return true;
    }

    /** Validates inputData with validationRules
     * @return bool
     */
    private function validate(): bool
    {
        $validator = Validator::make($this->getInputData(), $this->getValidationRules());

        if ($validator->fails()) {
            $this->setMessage($this->settingMessage()->get('oshibka-validacii'));
            return false;
        }

        return true;
    }

    /** Prepares inputFile
     * @return bool|mixed
     */
    private function prepareFile()
    {
        if (!$this->getFileFieldName()) {
            return false;
        }

        if (!isset($this->getInputData()[$this->getFileFieldName()])) {
            return false;
        }

        $file = $this->getInputData()[$this->getFileFieldName()];

        $fileName = md5_file($file->getPathName()) . '_' . time() . "." . $file->getClientOriginalExtension();

        $folderPath = $this->getFileStorageFolder() . date('Y/m/d') . "/";

        if (!$file->move($folderPath, $fileName)) {
            return false;
        }

        $this->inputData[$this->getFileFieldName()] = $folderPath . $fileName;

        return $file;
    }

    /** Converts raw input into prepared data
     * Should be overwritten in implementations
     * @param array $inputData
     * @return array
     */
    abstract protected function prepareInputData(array $inputData): array;

    /** Converts prepared data into mail data
     * Can be overwritten in implementations
     * @param array $preparedData
     * @return array
     */
    protected function prepareMailData(array $preparedData): array
    {
        return $preparedData;
    }

    /** Sends email letter with use Vis\MailTemplates\MailT class
     * @param array $preparedMailData
     * @return bool
     */
    private function sendMail(array $preparedMailData): bool
	{
        $mail = new MailT($this->getMailTemplate(), $preparedMailData);
        $mail->to = $this->settingEmail()->get($this->getMailAddressSlug());

		return $mail->send();
	}

    /** Custom callback method. Insert whatever additional logic you need here (Sending applyForm to API, etc.)
     * Can be overwritten in implementations
     * @param  array $attributes
     * @return bool
     */
    protected function customCallback(array $attributes)
    {
        return true;
    }

    /** Main method that fires ApplyForm saving
     * @return bool
     */
    private function fire()
    {
        if (!$this->validateCaptcha()) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $this->prepareFile();

        $preparedData = $this->prepareInputData($this->getInputData());
        $this->fill($preparedData);
        $this->save();

        if ($this->getMailTemplate()) {
            $this->sendMail($this->prepareMailData($preparedData));
        }

        $this->customCallback($this->getAttributes());

        return true;
    }

    /** Public method for externally calling fire() on ApplyForm
     * @return bool
     */
    final public function apply(): bool
    {
        $status = false;

        if (config('apply_form.apply_form.transaction_enabled')) {
            DB::beginTransaction();
            try {
                if ($status = $this->fire()) {
                    $this->setMessage($this->settingMessage()->get($this->getMessageSlug()));
                    DB::commit();
                }
            } catch (Exception $e) {
                $this->setMessage($this->settingMessage()->get('oshibka-sohraneniya'));
                DB::rollBack();
                Log::critical($e->getMessage());
            }
        } else {
            if ($status = $this->fire()) {
                $this->setMessage($this->settingMessage()->get($this->getMessageSlug()));
            }
        }

        return $status;
    }

}
