<?php
namespace Vis\ApplyForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use \Request;
use \Validator;
use \Setting;

use Vis\MailTemplates\MailT;

abstract class AbstractApplyForm extends Model
{
	protected $table;

	protected $fillable = [];

    private $inputCleaner;

    private $inputData = [];

    protected $validationRules = [];

    protected $fileFieldName = '';
    protected $fileStorageFolder = 'storage/apply_form_files/';

    protected $mailTemplate = '';
    protected $mailAddressSettingName  = '';

    private $message = '';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    final protected function inputCleaner(): ApplyFormInputCleaner
    {
        if (!$this->inputCleaner) {
            $this->inputCleaner = new ApplyFormInputCleaner();
        }

        return $this->inputCleaner;
    }

	private function getInputData(): array
	{
		return $this->inputData;
	}

	final public function setInputData(array $inputData)
	{
		$this->inputData = $inputData;

		return $this;
	}

    private function getValidationRules(): array
    {
        return $this->validationRules;
    }

    private function getFileFieldName(): string
    {
        return $this->fileFieldName;
    }

    private function getFileStorageFolder(): string
    {
        return $this->fileStorageFolder;
    }

    private function getMailTemplate(): string
	{
		return $this->mailTemplate;
	}

    private function getMailAddressSettingName(): string
	{
		return $this->mailAddressSettingName;
	}

    final public function setMessage(string $message)
    {
        $this->message = $message;
    }

    final public function getMessage(): string
    {
        return $this->message;
    }

    private function validateCaptcha(): bool
    {
        if (!isset($this->getInputData()['grecaptcha_response'])) {
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
            $this->setMessage('captcha');
            return false;
        }

        unset($this->getInputData()['grecaptcha_response']);

        return true;
    }

    private function validate(): bool
    {
        $validator = Validator::make($this->getInputData(), $this->getValidationRules());

        if ($validator->fails()) {
            $this->setMessage($validator->errors());
            return false;
        }

        return true;
    }

    private function prepareFile()
    {
        if (!$this->getFileFieldName()) {
            return false;
        }

        if (!isset($this->getInputData()[$this->getFileFieldName()])) {
            return false;
        }

        //fixme think about array of files
        $file = $this->getInputData()[$this->getFileFieldName()];

        $fileName = md5_file($file->getPathName()) . '_' . time() . "." . $file->getClientOriginalExtension();

        $folderPath = $this->getFileStorageFolder() . date('Y/m/d') . "/";

        if (!$file->move($folderPath, $fileName)) {
            return false;
        }

        $this->inputData[$this->getFileFieldName()] = $folderPath . $fileName;

        return $file;
    }

    abstract protected function prepareInputData(array $inputData): array;

    abstract protected function prepareMailData(array $preparedData): array;

	private function sendMail($preparedMailData): bool
	{
        if (!$this->getMailTemplate()) {
            return false;
        }

		$mail = new MailT($this->getMailTemplate(), $preparedMailData);
        $mail->to = Setting::get($this->getMailAddressSettingName());

		return $mail->send();
	}

	private function fire()
    {
        if (!$this->validateCaptcha()) {
            return false;
        }

        if (!$this->validate()) {
            return false;
        }

        $this->prepareFile();
        $preparedData     = $this->prepareInputData($this->getInputData());
        $preparedMailData = $this->prepareMailData($preparedData);

        $this->fill($preparedData);
        $this->save();

        $this->sendMail($preparedMailData);

        return true;
    }

    //fixme uncomment transaction
    final public function apply(): bool
    {
        //fixme redo responsing for this method
        if (config('apply_form.apply_form.transaction_enabled')) {
            DB::beginTransaction();
            try {
                $this->fire();
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
        } else {
            $this->fire();
        }

        return true;
    }

}
