<?php

namespace FileHosting\Helper;

use FileHosting\Repository\FileRepository;
use FileHosting\Repository\SearchRepository;

class SearchHelper
{
    private $searchRepository;
    private $fileRepository;

    public function __construct(SearchRepository $searchRepository, FileRepository $fileRepository)
    {
        $this->searchRepository = $searchRepository;
        $this->fileRepository = $fileRepository;
    }

    public function searchFiles($query)
    {
        $array = $this->searchRepository->getIdsByQuery($query);

        if (empty($array)) {
            return false;
        }

        $ids = $this->getIdsFromArray($array);

        $result = $this->fileRepository->getFilesByIds($ids);

        return $result;
    }

    private function getIdsFromArray(array $array): array
    {
        $ids = [];

        foreach ($array as $id) {
            $ids[] = $id['id'];
        }

        return $ids;
    }
}
