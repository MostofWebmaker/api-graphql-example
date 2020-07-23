<?php

namespace App\Service\FCM;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Exception\ClientException;

/**
 * Class FCMService
 * @package App\Service\FCM
 */
class FCMService
{
    /** @var LoggerInterface  */
    private LoggerInterface $logger;

    /** @var ClientInterface */
    private ?ClientInterface $client = null;

    /**
     * FCMService constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @return Client|ClientInterface
     */
    private function getClient()
    {
        if (!$this->client) {
            $this->client = new Client([
                'base_uri'        => $_ENV['EXPO_PUSH_URL'],
                'verify'          => false,
                'cookies'         => true,
                'allow_redirects' => false
            ]);
        }
        return $this->client;
    }

    /**
     * @param array $tokens
     * @param array $message
     */
    public function pushNotification(array $tokens, array $message)
    {
        if (empty($tokens) || empty($message)) {
            throw new ClientException('Массивы $tokens и $message не должны быть пустыми!', null);
        }
        try {
            $fields = [
                "to" => $tokens,
                "title" => $message['title'],
                "body" => $message['message']
            ];
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                ],
                'body' => json_encode($fields),
                'http_errors' => false
            ];
            $response = $this->getClient()->post('', $options);
            $code = $response ?  $response->getStatusCode() : null;
            if ($code != 200) {
                $tokensString = implode(', ', $tokens);
                throw new \RuntimeException("'Отправка push уведомления для device_ids {$tokensString} завершилась неудачей!");
            }
        } catch (\RuntimeException $e) {
            $this->logger->critical($e->getMessage());
            $response->getBody()->getContents();
            throw new \RuntimeException($response);
        }
    }
}
