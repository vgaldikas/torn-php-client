<?php

declare(strict_types=1);

/*
 * This file is part of Torn PHP Client.
 *
 * (c) Vytautas Galdikas <galdikas.vytautas@gmail.com>
 */

namespace VGaldikas\Torn;

use GuzzleHttp\Client;

class Torn
{
    /**
     * Base url for Torn.com HTTP
     *
     * @const string
     */
    const BASE_URL = 'https://api.torn.com';

    /**
     * The API key used for authentication
     *
     * @var string
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param int   $id
     * @param array $selections
     *
     * @return array
     */
    public function getUser(int $id, array $selections = []): array
    {
        return $this->send('/user/' . $id, $selections);
    }

    /**
     * @param int   $id
     * @param array $selections
     *
     * @return array
     */
    public function getFaction(int $id, array $selections = []): array
    {
        return $this->send('/faction/' . $id, $selections);
    }

    /**
     * @param int   $id
     * @param array $selections
     *
     * @return array
     */
    public function getProperty(int $id, array $selections = []): array
    {
        return $this->send('/property/' . $id, $selections);
    }

    /**
     * @param int   $id
     * @param array $selections
     *
     * @return array
     */
    public function getCompany(int $id, array $selections = []): array
    {
        return $this->send('/company/' . $id, $selections);
    }

    /**
     * @return array
     */
    public function getPointsMarket()
    {
        return $this->send('/market/pointsmarket', ['pointsmarket']);
    }

    /**
     * @param int   $id
     * @param array $selections
     *
     * @return array
     */
    public function getMarket(int $id, array $selections = []): array
    {
        return $this->send('/market' . $id, $selections);
    }

    /**
     * @return array
     */
    public function getAllItems(): array
    {
        return $this->send('/torn', ['items']);
    }

    /**
     * @param int $itemId
     *
     * @return array
     */
    public function getItemById(int $itemId): array
    {
        $result = $this->send('/torn/' . $itemId, ['items']);

        if (!empty($result['items'][$itemId])) {
            return $result['items'][$itemId];
        }

        return [];
    }

    /**
     * @param string $path
     * @param array  $selections
     *
     * @return array
     * @throws \Exception
     */
    private function send(string $path, array $selections = []): array
    {
        $httpClient = new Client();

        $result = $httpClient->request(
            'GET',
            self::BASE_URL . $path . '?key=' . $this->key . $this->parseSelections($selections)
        );

        $contents = $result->getBody()->getContents();

        $responseContent = json_decode($contents, true);

        if (!empty($responseContent['error'])) {
            throw new \Exception(
                'Torn API responded with error: ' . $responseContent['error']['error'],
                $responseContent['error']['code']
            );
        }

        return $responseContent;
    }

    /**
     * @param array $selections
     *
     * @return string
     */
    private function parseSelections(array $selections): string
    {
        if (empty($selections)) {
            return '';
        }

        return '&selections=' . implode(',', $selections);
    }
}
