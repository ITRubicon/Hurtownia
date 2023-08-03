<?php

namespace App\Repository\Tema;

use App\Repository\IApiRepository;
use Doctrine\DBAL\ParameterType;

class PrepaymentDocumentRepository extends IApiRepository
{
    private string $endpoint = '/api/dms/v1/prepayment-invoices/{branchId}';
    private $documentEndpoints = [];
    private $documentItems = [];
    protected $table = 'tema_prepayment_document';

    public function fetch(): array
    {
        $this->clearDataArrays();
        $this->getDocumentEndpointList();
        $listCount = count($this->documentEndpoints);

        if ($listCount) {
            echo "\nPobieram zapisy dokumentów";

            for ($i=0; $i < $listCount; $i++) { 
                echo "\nEndpoint ----> $i/$listCount";
                $doc = $this->fetchApiResult($this->documentEndpoints[$i]['getUrl']);
                unset($this->documentEndpoints[$i]);

                if (empty($doc))
                    continue;

                $this->collectItems($doc);
                array_push($this->fetchResult, $doc);
            }

            $this->save();
            $this->fetchResult = [];
        }

        return [
            'fetched' => $listCount,
            'items' => $this->documentItems
        ];
    }

    private function getDocumentEndpointList()
    {
        $stocks = $this->getStocks();
        $stocksCount = count($stocks);

        if ($stocksCount) {
            echo "\nPobieranie endpointów dla oddziałów";
            $i = 1;

            foreach ($stocks as $stock) {
                echo "\nNr stocku $stock ----> $i/$stocksCount";
                $i++;

                $url = str_replace('{branchId}', $stock, $this->endpoint);
                $res = $this->fetchApiResult($url);
                if (empty($res))
                    continue;

                $this->documentEndpoints = array_merge($this->documentEndpoints, $res);
            }
        } else 
            throw new \Exception("Nie żadnych jednostek organizacyjnych. Najpierw uruchom komendę pobierającą listę jednostek [tema:stock]", 99);
    }

    protected function getFieldsParams(): array
    {
        return [
            'doc_id' => ['sourceField' => 'id', 'type' => ParameterType::STRING],
            'name' => ['sourceField' => 'name', 'type' => ParameterType::STRING],
            'vin' => ['sourceField' => 'vin', 'type' => ParameterType::STRING],
            'issue_date' => ['sourceField' => 'issueDate', 'type' => ParameterType::STRING, 'format' => ['date' => 'Y-m-d H:i:s']],
            'net_value' => ['sourceField' => 'netValue', 'type' => ParameterType::STRING],
            'gross_value' => ['sourceField' => 'grossValue', 'type' => ParameterType::STRING],
            'notes' => ['sourceField' => 'notes', 'type' => ParameterType::STRING],
            'source' => ['sourceField' => 'source', 'type' => ParameterType::STRING],
        ];
    }

    private function collectItems(array &$doc)
    {
        foreach ($doc['items'] as $item) {
            $item['doc_id'] = $doc['id'];
            array_push($this->documentItems, $item);
        }
        unset($doc['items']);
    }

    private function getStocks()
    {
        
        $q = "SELECT stock_id FROM tema_stock WHERE source = :source";
        return $this->db->fetchFirstColumn($q, ['source' => $this->source->getName()], ['source' => ParameterType::STRING]);
    }

    protected function clearDataArrays()
    {
        $this->fetchResult = [];
        $this->documentEndpoints = [];
        $this->documentItems = [];
    }
}