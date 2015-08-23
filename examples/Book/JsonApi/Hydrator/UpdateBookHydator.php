<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Hydrator;

use WoohooLabs\Yin\Examples\Book\Repository\BookRepository;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\AbstractUpdateHydrator;

class UpdateBookHydator extends AbstractUpdateHydrator
{
    /**
     * @return string
     */
    protected function getAcceptedType()
    {
        return "book";
    }

    /**
     * @param array $resource
     * @param string $id
     * @return mixed|null
     */
    protected function setId($resource, $id)
    {
        $resource["id"] = $id;

        return $resource;
    }

    /**
     * @param array $resource
     * @return array
     */
    protected function getAttributeHydrator($resource)
    {
        return [
            "title" => function(array $resource, $attribute, $data)  { $resource["title"] = $attribute; return $resource; },
            "pages" => function(array &$resource, $attribute, $data) { $resource["pages"] = $attribute; }
        ];
    }

    /**
     * @param array $resource
     * @return array
     */
    protected function getRelationshipHydrator($resource)
    {
        return [
            "authors" => function(array $resource, ToManyRelationship $authors, $data) {
                $resource["authors"] = BookRepository::getAuthors($authors->getResourceIdentifierIds());

                return $resource;
            },
            "publisher" => function(array &$resource, ToOneRelationship $publisher, $data) {
                $resource["publisher"] = BookRepository::getPublisher($publisher->getResourceIdentifier()->getId());
            }
        ];
    }
}
