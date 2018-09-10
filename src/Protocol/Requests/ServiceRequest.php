<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 05.09.18
 * Time: 17:52
 */

namespace Micseres\ServiceHub\Protocol\Requests;

use ReflectionClass;

/**
 * Class ServiceRequest
 * @package Micseres\ServiceHub\Protocol\Requests
 */
final class ServiceRequest extends Request
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