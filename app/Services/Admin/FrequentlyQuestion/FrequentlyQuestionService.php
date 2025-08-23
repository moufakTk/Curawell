<?php

namespace App\Services\Admin\FrequentlyQuestion;

use App\Models\FrequentlyQuestion;

class FrequentlyQuestionService
{
    public function create(array $data): FrequentlyQuestion
    {
        return FrequentlyQuestion::create($data);
    }

    public function update(FrequentlyQuestion $fq, array $data): FrequentlyQuestion
    {
        $fq->update($data);
        return $fq;
    }

    public function delete(FrequentlyQuestion $fq): void
    {
        $fq->delete();
    }
}
