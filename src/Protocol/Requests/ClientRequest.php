<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 16:24
 */

namespace Micseres\ServiceHub\Protocol\Requests;


/**
 * Class ClientRequest
 * @package Micseres\ServiceHub\Protocol\Requests
 */
class ClientRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'protocol' => 'required',
            'action' => 'required',
            'route' => 'required',
            'message' => 'required',
            'payload' => 'required',
        ];
    }

    /**
     * @return array|null
     */
    public function validate(): ?array
    {
        $messages = [];

        foreach ($this->rules() as $key => $value) {
            if ($value === 'required' && null === $this->{'get'.ucfirst($key)}()) {
                $messages[] = [$key => $value];
            }
        }

        if (count($messages) > 0) {
            return $messages;
        }

        return null;
    }
}