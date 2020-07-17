<?php

namespace App\Generators;

use App\Service\FCM\FCMService;

/**
 * Class PushGenerator
 * @package App\Generators
 */
class PushGenerator
{
    /**
     * @var array
     */
    private ?array $tokens = null;
    /**
     * @var FCMMessage
     */
    private ?FCMMessage $FCMmessage = null;

    /**
     * @var FCMService
     */
    private FCMService $FCMservice;

    /**
     * PushGenerator constructor.
     * @param array $tokens
     * @param FCMMessage $FCMmessage
     * @param FCMService $FCMService
     */
    public function __construct(FCMService $FCMService)
    {
        $this->FCMservice = $FCMService;
    }


//    /**
//     * @param array $tokens
//     * @param FCMMessage $FCMmessage
//     * @param FCMService $FCMService
//     */
//    public function build(array $tokens, FCMMessage $FCMmessage, FCMService $FCMService) {
//        if (empty($tokens)) {
//            throw new \RuntimeException('Массив $tokens не должен быть пустым!');
//        }
//        $this->tokens = $tokens;
//        $this->FCMmessage = $FCMmessage;
//        $this->FCMservice = new FCMService(new Logger());
//    }
//
//    public function isBuild(): bool
//    {
//        return ($this->getTokens() && $this->getFCMmessage() && $this->getFCMservice());
//    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param array $tokens
     */
    public function setTokens(array $tokens): void
    {
        if (empty($tokens)) {
            throw new \RuntimeException('Массив $tokens не должен быть пустым!');
        }
        $this->tokens = $tokens;
    }

    /**
     * @return FCMMessage
     */
    public function getFCMmessage(): FCMMessage
    {
        return $this->FCMmessage;
    }

    /**
     * @param FCMMessage $FCMmessage
     */
    public function setFCMmessage(FCMMessage $FCMmessage): void
    {
        $this->FCMmessage = $FCMmessage;
    }

    /**
     * @return FCMService
     */
    public function getFCMservice(): FCMService
    {
        return $this->FCMservice;
    }

    /**
     * @param FCMService $FCMservice
     */
    public function setFCMservice(FCMService $FCMservice): void
    {
        $this->FCMservice = $FCMservice;
    }

    /**
     * @return array
     */
    private function getFields(): array
    {
        return [
            'message' 	=> $this->getFCMmessage()->getMessage(),
            'title'		=> $this->getFCMmessage()->getTitle(),
//            'subtitle'	=> $this->getFCMmessage()->getSubTitle(),
//            'vibrate'	=> $this->getFCMmessage()->getVibrate(),
//            'sound'		=> $this->getFCMmessage()->getSound(),
//            'largeIcon'	=> $this->getFCMmessage()->getLargeIcon(),
//            'smallIcon'	=> $this->getFCMmessage()->getSmallIcon()
        ];
    }


    public function generate(): void
    {
        $this->getFCMservice()->pushNotification($this->getTokens(), $this->getFields());
    }
}
