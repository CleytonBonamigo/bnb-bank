<?php

namespace Turno\Common\Traits;

use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\ArraySerializer;

trait TransformerUtility {

    /**
     * @param $data
     * @param bool $isCollection
     * @return array[]|null
     */
    private function transform($data, bool $isCollection = true): ?array
    {
        if (empty($data)) {
            return $isCollection ? ['data' => []] : null;
        }

        $response = $isCollection
            ? new Collection($data, $this->transformer)
            : new Item($data, $this->transformer);

        return $this->formatResponse($response);
    }

    private function formatResponse($response): array
    {
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($response)->toArray();
    }
}
