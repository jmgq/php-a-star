<?php

namespace JMGQ\AStar\Example\Graph;

class Graph
{
    /** @var Link[] */
    private array $links = [];

    /**
     * @param Link[] $links
     */
    public function __construct(iterable $links = [])
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    public function addLink(Link $link): void
    {
        $linkId = $this->getLinkId($link->getSource(), $link->getDestination());

        $this->links[$linkId] = $link;
    }

    public function getLink(Coordinate $source, Coordinate $destination): ?Link
    {
        if ($this->hasLink($source, $destination)) {
            $linkId = $this->getLinkId($source, $destination);

            return $this->links[$linkId];
        }

        return null;
    }

    public function hasLink(Coordinate $source, Coordinate $destination): bool
    {
        $linkId = $this->getLinkId($source, $destination);

        return isset($this->links[$linkId]);
    }

    /**
     * @param Coordinate $node
     * @return Coordinate[]
     */
    public function getDirectSuccessors(Coordinate $node): array
    {
        $successors = [];

        foreach ($this->links as $link) {
            if ($node->getId() === $link->getSource()->getId()) {
                $successors[] = $link->getDestination();
            }
        }

        return $successors;
    }

    private function getLinkId(Coordinate $source, Coordinate $destination): string
    {
        return $source->getId() . '|' . $destination->getId();
    }
}
