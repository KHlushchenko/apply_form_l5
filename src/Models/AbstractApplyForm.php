<?php
namespace Vis\ApplyForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use \Request;
use \Validator;

use Vis\MailTemplates\MailT;

use Vis\ApplyForm\Helpers\InputCleaner;

abstract class AbstractApplyForm extends Model
{
	protected $table;

	protected $fillable = [];

    private $inputCleaner;
    private $settingEmail;
    private $settingMessage;

    private $inputData = [];

    protected $validationRules = [];

    protected $fileFieldName     = '';
    protected $fileStorageFolder = 'storage/apply_form_files/';

    protected $mailTemplate     = '';
    protected $mailAddressSlug  = '';

    protected $messageSlug      = 'uspeh-sohraneniya';

    private $message = [
        'title'         => '',
        'description'   => '',
    ];

    final protected function inputCleaner(): InputCleaner
    {
        if (!$this->inputCleaner) {
            $this->inputCleaner = new InputCleaner();
        }

        return $this->inputCleaner;
    }

    final protected function settingMessage(): ApplyFormSettingMessage
    {
        if (!$this->settingMessage) {
            $this->settingMessage = new ApplyFormSettingMessage();
        }

        return $this->settingMessage;
    }

    final protected function settingEmail(): ApplyFormSettingEmail
    {
        if (!$this->settingEmail) {
            $this->settingEmail = new ApplyFormSettingEmail();
        }

        return $this->settingEmail;
    }

    final public function setInputData(array $inputData)
    {
        $this->inputData = $inputData;

        return $this;
    }
	private function getInputData(): array
	{
		return $this->inputData;
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

    private function getMailAddressSlug(): string
	{
		return $this->mailAddressSlug;
	}

    /**
     * @return string
     */
    private function getMessageSlug(): string
    {
        return $this->messageSlug;
    }

    private function setMessage(array $message)
    {
        $this->message = $message;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    private function validateCaptcha(): bool
    {
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

    private function validate(): bool
    {
        $validator = Validator::make($this->getInputData(), $this->getValidationRules());

        if ($validator->fails()) {
            $this->setMessage($this->settingMessage()->get('oshibka-validacii'));
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
        $mail->to = $this->settingEmail()->get($this->getMailAddressSlug());

		return $mail->send();
	}

	protected function customCallback()
    {
        return true;
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

        $this->customCallback();

        return true;
    }

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
            }
        } else {
            if ($status = $this->fire()) {
                $this->setMessage($this->settingMessage()->get($this->getMessageSlug()));
            }
        }

        return $status;
    }

}
