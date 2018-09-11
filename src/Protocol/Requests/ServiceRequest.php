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
            $method = 'get'.ucfirst($key);
            if (method_exists($this,$method)) {
                if ($value === 'required' && null === $this->{$method}()) {
                    $messages[] = [$key => $value];
                }
            }
        }

        if (count($messages) > 0) {
            return $messages;
        }

        return null;
    }
}