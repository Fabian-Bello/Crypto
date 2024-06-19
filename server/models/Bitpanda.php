<?php

class Bitpanda implements JsonSerializable
{
    private $btcData;

    public function __construct()
    {
        $this->callBit();
    }

    private function callBit()
    {
        // Die URL der API, die Sie aufrufen möchten
        $url = 'https://api.bitpanda.com/v1/ticker';

        // API-Aufruf mit file_get_contents
        $response = file_get_contents($url);

        if ($response === FALSE) {
            die('Fehler beim Abrufen der Daten');
        }

        // Antwort verarbeiten
        $data = json_decode($response, true);

        if ($data === NULL) {
            die('Fehler beim Dekodieren der JSON-Daten');
        }

        // Nur die BTC-Daten extrahieren
        if (isset($data['BTC'])) {
            $this->btcData = $data['BTC'];
        } else {
            die('BTC-Daten nicht gefunden');
        }
    }

    public function jsonSerialize()
    {
        return $this->btcData;
    }
}

header('Content-Type: application/json');

try {
    $bitpanda = new Bitpanda();
    echo json_encode($bitpanda);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
